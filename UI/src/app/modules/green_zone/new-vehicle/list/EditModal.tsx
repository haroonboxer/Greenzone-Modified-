import React, { useEffect, useState } from 'react'
import { Modal, Button, Form } from 'react-bootstrap'
import { useAppDispatch } from 'redux/hooks'
import { useTranslation } from 'react-i18next'
import { updateVehicleSave } from 'redux/green_zone/vehicleSave/vehicleSaveSlice'

interface EditModalProps {
    show: boolean
    onHide: () => void
    onSuccess: () => void
    vehicle: { id: number; name: string } | null
}

const EditModal: React.FC<EditModalProps> = ({ show, onHide, onSuccess, vehicle }) => {
    const { t } = useTranslation()
    const dispatch = useAppDispatch()
    const [name, setName] = useState('')
    const [loading, setLoading] = useState(false)
    const [error, setError] = useState('')

    useEffect(() => {
        if (vehicle) {
            setName(vehicle.name)
            setError('')
        }
    }, [vehicle])

    const validateName = (value: string) => {
        if (!value.trim()) return t('newVehicles.vehicle_name') + ' ' + t('global.is_required')
        if (value.length > 50) return t('global.maxLength') + ' 50'
        return ''
    }

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault()
        if (!vehicle) return

        const validationError = validateName(name)
        if (validationError) {
            setError(validationError)
            return
        }

        setLoading(true)
        try {
            await dispatch(updateVehicleSave({ id: vehicle.id, formData: { name } })).unwrap()
            onSuccess()
            onHide()
            setError('')
        } catch (error) {
            console.error('Update failed:', error)
            setError(t('global.saveFailed'))
        } finally {
            setLoading(false)
        }
    }

    const handleChange = (value: string) => {
        setName(value)
        if (error) setError('') // clear error while typing
    }

    return (
        <Modal show={show} onHide={onHide} centered>
            <Form onSubmit={handleSubmit}>
                <Modal.Header closeButton>
                    <Modal.Title>
                        <i className="fa-solid fa-edit text-warning"></i>&nbsp;
                        {t('global.edit', { name: t('newVehicles.vehicle') })}
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
                        {loading ? t('global.saving') + '...' : t('global.update')}
                    </Button>
                </Modal.Footer>
            </Form>
        </Modal>
    )
}

export default EditModal
