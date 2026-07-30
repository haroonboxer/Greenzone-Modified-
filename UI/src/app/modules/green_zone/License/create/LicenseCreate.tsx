import React, {useEffect, useState} from 'react'
import {useDispatch} from 'react-redux'
import {AppDispatch} from 'redux/store'
import {toast} from 'react-toastify'
import {useTranslation} from 'react-i18next'
import {useParams} from 'react-router-dom'
import LicenseCreateForm from './LicenseCreateForm'
import {defaultLicense, License} from '../__model'
import persian_fa from 'helpers/persian_fa'
import DatePicker from 'react-multi-date-picker'
import persian from 'react-date-object/calendars/persian'
import {storeLicense} from 'redux/green_zone/license/licenseSlice'

interface LicenseCreateProps {
  showModal: boolean
  setShowModal: React.Dispatch<React.SetStateAction<boolean>>
  onSuccess: () => void
}

const LicenseCreate: React.FC<LicenseCreateProps> = ({showModal, setShowModal, onSuccess}) => {
  const dispatch = useDispatch<AppDispatch>()
  const {t} = useTranslation()
  const [loading, setLoading] = useState(false)
  const [formData, setFormData] = useState<License>(defaultLicense)
  const [files, setFiles] = useState<File[]>([])
  const fileType = ['JPEG', 'PNG', 'JPG', 'PDF']
  const {id} = useParams<{id: string}>()

  const handleDrop = (fileList: File[]) => {
    setFiles([...files, ...fileList])
  }

  const handleFileRemove = (index: number) => {
    const newFiles = [...files]
    newFiles.splice(index, 1)
    setFiles(newFiles)
  }

  useEffect(() => {
    if (!showModal) {
      setFormData(defaultLicense)
      setFiles([])
    }
  }, [showModal])

  const handleSubmit = async (values: License) => {
    setLoading(true)
    try {
      const formData = new FormData()
      formData.append('issue_date', values.issue_date)
      formData.append('expire_date', values.expire_date)
      formData.append('license_type', values.license_type)
      if (id) {
        formData.append('vehicle_id', id)
      }
      files.forEach((file) => {
        formData.append('attachments[]', file)
      })

      await dispatch(storeLicense(formData)).unwrap()
      toast.success(t('License created successfully!'))
      setShowModal(false)
      onSuccess()
    } catch (error: any) {
      const key = error?.key || 'gzlicense.save_error'
      toast.error(t(key))
    } finally {
      setLoading(false)
    }
  }

  return (
    <LicenseCreateForm
      showModal={showModal}
      handleClose={() => setShowModal(false)}
      handleSubmit={handleSubmit}
      initialData={formData}
      loading={loading}
      handleDrop={handleDrop}
      handleFileRemove={handleFileRemove}
      fileType={fileType}
      files={files}
      DatePicker={DatePicker}
      persian_fa={persian_fa}
      persian={persian}
    />
  )
}

export default LicenseCreate
