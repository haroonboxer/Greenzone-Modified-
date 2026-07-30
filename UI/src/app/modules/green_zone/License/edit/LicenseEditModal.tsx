import React, { useState, useEffect } from 'react'
import { useFormik } from 'formik'
import * as Yup from 'yup'
import { useTranslation } from 'react-i18next'
import { Modal, Button, Form, Row, Col, Spinner, FormGroup, FormLabel, FormSelect } from 'react-bootstrap'
import { FileUploader } from 'react-drag-drop-files'
import DatePicker, { DateObject } from 'react-multi-date-picker'
import persian from 'react-date-object/calendars/persian'
import persian_fa from 'helpers/persian_fa'
import { useAppDispatch } from 'redux/hooks'
import { updateLicense } from 'redux/green_zone/license/licenseSlice'
import { toast } from 'react-toastify'

interface LicenseEditModalProps {
  licenseId: any
  showModal: boolean
  onHide: () => void
  onSuccess: () => void
}

const LicenseEditModal: React.FC<LicenseEditModalProps> = ({ licenseId, showModal, onHide, onSuccess }) => {
  const { t } = useTranslation()
  const dispatch = useAppDispatch()
  const [loading, setLoading] = useState(false)
  const [files, setFiles] = useState<File[]>([])
  const [issueDate, setIssueDate] = useState<DateObject | null>(null)
  const [expireDate, setExpireDate] = useState<DateObject | null>(null)

  const fileType = ['JPEG', 'PNG', 'JPG', 'PDF']

  const validationSchema = Yup.object().shape({
    license_type: Yup.string().required(t('license.license_type_is_required')),
    issue_date: Yup.string().required(t('license.issue_date_is_required')),
    expire_date: Yup.string().required(t('license.expire_date_is_required')),
  })

  const formik = useFormik({
    initialValues: {
      license_type: licenseId?.license_type || '',
      issue_date: licenseId?.issue_date || '',
      expire_date: licenseId?.expire_date || '',
    },
    validationSchema,
    enableReinitialize: true,
    onSubmit: async (values) => {
      setLoading(true)
      try {
        const formData = new FormData()
        formData.append('license_type', values.license_type)
        formData.append('issue_date', values.issue_date)
        formData.append('expire_date', values.expire_date)

        files.forEach((file) => {
          formData.append('attachments[]', file)
        })

        await dispatch(updateLicense({ id: licenseId.id, formData })).unwrap()
        toast.success(t('License updated successfully!'))
        onSuccess()
      } catch (err) {
        toast.error(t('Error updating license!'))
      } finally {
        setLoading(false)
      }
    },
  })

  const handleDateChange = (date: DateObject | DateObject[] | null, field: string) => {
    if (date && !Array.isArray(date)) {
      const formattedDate = `${date.year}-${date.month.number}-${date.day}`
      formik.setFieldValue(field, formattedDate)
      if (field === 'issue_date') setIssueDate(date)
      if (field === 'expire_date') setExpireDate(date)
    } else {
      formik.setFieldValue(field, '')
      if (field === 'issue_date') setIssueDate(null)
      if (field === 'expire_date') setExpireDate(null)
    }
  }

  const handleFileChange = (newFiles: FileList | null) => {
    if (newFiles) setFiles([...files, ...Array.from(newFiles)])
  }

  const handleFileRemove = (index: number) => {
    const newFiles = [...files]
    newFiles.splice(index, 1)
    setFiles(newFiles)
  }

  useEffect(() => {
    if (showModal) {
      if (licenseId?.issue_date) {
        setIssueDate(new DateObject({ date: licenseId.issue_date, calendar: persian }))
      }
      if (licenseId?.expire_date) {
        setExpireDate(new DateObject({ date: licenseId.expire_date, calendar: persian }))
      }
    } else {
      formik.resetForm()
      setFiles([])
      setIssueDate(null)
      setExpireDate(null)
    }
  }, [showModal, licenseId])

  const RequiredLabel = ({ label }: { label: string }) => (
    <FormLabel>
      {label} <span className='text-danger'>*</span>
    </FormLabel>
  )

  return (
    <Modal show={showModal} onHide={onHide} size='lg' backdrop='static'>
      <Modal.Header closeButton>
        <Modal.Title>{t('license.edit_license')}</Modal.Title>
      </Modal.Header>
      <Form onSubmit={formik.handleSubmit}>
        <Modal.Body>
          <Row>
            <Col md={6}>
              <FormGroup className='mb-3'>
                <RequiredLabel label={t('license.issue_date')} />
                <DatePicker
                  calendar={persian}
                  locale={persian_fa}
                  value={issueDate}
                  onChange={(date: any) => handleDateChange(date, 'issue_date')}
                  containerStyle={{ width: '100%' }}
                  style={{ width: '100%', height: '38px' }}
                  format='YYYY-MM-DD'
                  placeholder={t('gzlicense.select_issue_date')}
                />
              </FormGroup>
            </Col>
            <Col md={6}>
              <FormGroup className='mb-3'>
                <RequiredLabel label={t('gzlicense.expire_date')} />
                <DatePicker
                  calendar={persian}
                  locale={persian_fa}
                  value={expireDate}
                  onChange={(date: any) => handleDateChange(date, 'expire_date')}
                  containerStyle={{ width: '100%' }}
                  style={{ width: '100%', height: '38px' }}
                  format='YYYY-MM-DD'
                  placeholder={t('gzlicense.select_expire_date')}
                />
              </FormGroup>
            </Col>
          </Row>
          <Row>
            <Col md={6}>
              <FormGroup className='mb-3'>
                <RequiredLabel label={t('gzlicense.license_type')} />
                <FormSelect
                  name='license_type'
                  value={formik.values.license_type}
                  onChange={formik.handleChange}
                  onBlur={formik.handleBlur}
                  isInvalid={!!(formik.touched.license_type && formik.errors.license_type)}
                >
                  <option value=''>{t('gzlicense.select_license_type')}</option>
                  <option value='new'>{t('gzlicense.new')}</option>
                  <option value='extend'>{t('gzlicense.extend')}</option>
                  <option value='renew'>{t('gzlicense.renew')}</option>
                </FormSelect>
              </FormGroup>
            </Col>
            <Col md={6}>
              <FormGroup className='mb-3'>
                <FormLabel>{t('global.attachments')}</FormLabel>
                <FileUploader
                  handleChange={handleFileChange}
                  name='attachments'
                  types={fileType}
                  multiple
                  maxSize={30}
                  label={t('file_upload.drag_drop_or_click')}
                />
                {files.length > 0 && (
                  <div className='mt-3'>
                    {files.map((file, index) => (
                      <div key={index} className='d-flex justify-content-between'>
                        <span>{file.name}</span>
                        <button
                          type='button'
                          onClick={() => handleFileRemove(index)}
                          className='btn btn-sm btn-link text-danger'
                        >
                          <i className='fa fa-times'></i>
                        </button>
                      </div>
                    ))}
                  </div>
                )}
              </FormGroup>
            </Col>
          </Row>
        </Modal.Body>
        <Modal.Footer>
          <Button variant='primary' type='submit' disabled={loading}>
            {loading ? (
              <>
                <Spinner as='span' size='sm' animation='border' /> {t('global.saving')}
              </>
            ) : (
              t('global.save')
            )}
          </Button>
          <Button variant='danger' onClick={onHide} disabled={loading}>
            {t('global.close')}
          </Button>
        </Modal.Footer>
      </Form>
    </Modal>
  )
}

export default LicenseEditModal
