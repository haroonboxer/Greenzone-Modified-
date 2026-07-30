import React, { useState } from 'react'
import { Modal, Button, Form } from 'react-bootstrap'
import { useAppDispatch } from 'redux/hooks'
import { storeVehicleSave } from 'redux/green_zone/vehicleSave/vehicleSaveSlice'
import { useTranslation } from 'react-i18next'

interface CreateModalProps {
  show: boolean
  onHide: () => void
  onSuccess: () => void
}

const CreateModal: React.FC<CreateModalProps> = ({ show, onHide, onSuccess }) => {
  const { t } = useTranslation()
  const dispatch = useAppDispatch()
  const [name, setName] = useState('')
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')

  const validateName = (value: string) => {
    if (!value.trim()) return t('newVehicles.vehicle_name') + ' ' + t('global.is_required')
    if (value.length > 50) return t('global.maxLength') + ' 50'
    return ''
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    const validationError = validateName(name)
    if (validationError) {
      setError(validationError)
      return
    }

    setLoading(true)
    try {
      const res = await dispatch(storeVehicleSave({ name })).unwrap()
      if (res) {
        onSuccess()  // refresh table
        onHide()     // close modal
        setName('')  // reset input
        setError('') // clear error
      }
    } catch (error) {
      console.error('Failed to save vehicle:', error)
      setError(t('global.saveFailed'))
    } finally {
      setLoading(false)
    }
  }

  const handleChange = (value: string) => {
    setName(value)
    if (error) setError('') // clear error on input
  }

  return (
    <Modal show={show} onHide={onHide} centered>
      <Form onSubmit={handleSubmit}>
        <Modal.Header closeButton>
          <Modal.Title>
            <i className="fa-solid fa-plus text-primary"></i>&nbsp;
            {t('global.add', { name: t('newVehicles.vehicle') })}
          </Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <Form.Group controlId="vehicleName">
            <Form.Label className="fw-bold">
              {t('newVehicles.vehicle_name')}
            </Form.Label>
            <Form.Control
              type="text"
              placeholder={t('newVehicles.vehicle_name')}
              value={name}
              onChange={(e) => handleChange(e.target.value)}
              isInvalid={!!error}
              maxLength={50}
              required
            />
            <Form.Control.Feedback type="invalid">
              {error}
            </Form.Control.Feedback>
          </Form.Group>
        </Modal.Body>
        <Modal.Footer>
          <Button variant="secondary" onClick={onHide}>
            {t('global.cancel')}
          </Button>
          <Button type="submit" variant="primary" disabled={loading}>
            {loading ? t('global.saving') + '...' : t('global.save')}
          </Button>
        </Modal.Footer>
      </Form>
    </Modal>
  )
}

export default CreateModal
