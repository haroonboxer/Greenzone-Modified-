import { useState, useEffect } from 'react'
import { useDispatch } from 'react-redux'
import { toast } from 'react-toastify'
import { t } from 'i18next'
import { AppDispatch } from 'redux/store'
import { Modal, Button, Col, Row } from 'react-bootstrap'
import { changeStatus } from 'redux/green_zone/driver/driverSlice'
import { useTranslation } from 'react-i18next'

interface StatusModalProps {
  showModal: boolean
  setShowModal: React.Dispatch<React.SetStateAction<boolean>>
  onSuccess: () => void
  currentStatus: number
  vehicle_id: number
}

export default function StatusModal({
  showModal,
  setShowModal,
  onSuccess,
  currentStatus,
  vehicle_id,
}: StatusModalProps) {
  const { t } = useTranslation()
  const [status, setStatus] = useState<number>(currentStatus || 0)
  const dispatch = useDispatch<AppDispatch>()
  const [reasonDismissed, setReasonDismissed] = useState<string>('')
  const [files, setFiles] = useState<File[]>([])
  const handleDrop = (fileList: File[]) => setFiles([...files, ...fileList])
  const handleClose = () => {
    setShowModal(false)
    setFiles([])
    setReasonDismissed('')
  }

  const handleSave = () => {
    if (!vehicle_id) {
      toast.error(t('error.invalid_vehicle_id'))
      return
    }

    // 🔹 Frontend validation: reason required when dismissing
    if (status === 0 && reasonDismissed.trim() === '') {
      toast.error(t('error.reason_required')) // You can define this key in your i18n files
      return
    }


    const formData = new FormData()
    formData.append('id', vehicle_id.toString())
    formData.append('status', status.toString())
    formData.append('reason_dismissed', reasonDismissed)

    const maxFileSize = 4 * 1024 * 1024
    files.forEach((file) => {
      if (file.size <= maxFileSize) {
        formData.append('attachments[]', file)
      } else {
        toast.error(`${file.name} ${t('error.file_too_large')}`)
      }
    })

    dispatch(changeStatus(formData as any))
      .unwrap()
      .then(() => {
        onSuccess()
        toast.success(t('success.status_updated'))
        handleClose()
      })
      .catch((err: any) => {
        if (err?.errorCode === 'active_driver_exists') {
          toast.error(t('error.active_driver_exists'))
        } else if (err?.message) {
          toast.error(err.message)
        } else {
          toast.error(t('error.unknown'))
        }
      })

  }

  useEffect(() => {
    if (showModal) {
      const modalElement = document.getElementById('statusModal')
      if (modalElement && window.bootstrap) {
        const bootstrapModal = new window.bootstrap.Modal(modalElement)
        bootstrapModal.show()
        return () => bootstrapModal.hide()
      } else {
        console.error('Bootstrap Modal is not available.')
      }
    }
  }, [showModal])
  return (
    <Modal show={showModal} onHide={handleClose} backdrop='static' keyboard={false}>
      <Modal.Header closeButton>
        <Modal.Title>{t('status.driver_edit')}</Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <Row>
          <Col>
            <select
              className='form-select'
              value={status}
              onChange={(e) => setStatus(Number(e.target.value))}
            >
              <option value={1}>{t('status.status_active')}</option>
              <option value={0}>{t('status.status_deactive')}</option>
            </select>
          </Col>
        </Row>

        <Row className='mt-4'>
          <Col>
            <label htmlFor='reason_dismissed'>{t('status.reason_dismissed')}</label>
            <textarea
              id='reason_dismissed'
              className='form-control mt-2'
              style={{ height: '100px' }}
              value={reasonDismissed}
              onChange={(e) => setReasonDismissed(e.target.value)}
            />
          </Col>
        </Row>
      </Modal.Body>
      <Modal.Footer className='d-flex justify-content-between'>
        <Button variant='primary' onClick={handleSave}>
          {t('status.save')}
        </Button>
        <Button variant='danger' onClick={handleClose}>
          {t('status.close')}
        </Button>
      </Modal.Footer>
    </Modal>
  )
}
