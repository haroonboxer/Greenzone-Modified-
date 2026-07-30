import CustomModal from 'app/customes/CustomModal'
import AttachmentViewer from 'app/modules/authentication/components/attachment/view-attachment/AttachmentViewer'
import RecordOwnerView from 'helpers/CustomeRecordOwnerView'
import {t} from 'i18next'
import React, {useEffect, useState} from 'react'
import {Modal, Button, Row, Col} from 'react-bootstrap'
import {useAppDispatch, useAppSelector} from 'redux/hooks'
import {viewGzLicense} from 'redux/green_zone/license/licenseSlice'

interface Props {
  id: number
  onClose: () => void
}

const ViewPrintedCard: React.FC<Props> = ({id, onClose}) => {
  const dispatch = useAppDispatch()
  const {licenseView, loading, error} = useAppSelector((state) => state.gzLicense)
  const [isModalOpen, setIsModalOpen] = useState(false)
  const [contentType, setContentType] = useState('')

  const openModal = (contentType = '') => {
    setContentType(contentType)
    setIsModalOpen(true)
  }

  const closeModal = () => {
    setIsModalOpen(false)
    setContentType('')
  }

  useEffect(() => {
    if (id) {
      dispatch(viewGzLicense({id}))
    }
  }, [id, dispatch])

  const baseUrl = 'http://localhost/GreenZone/APP/' // your app base URL
  // const baseUrl = 'http://10.108.225.12:443/APP/' // your app base URL
  const frontPhotoUrl =
    licenseView && licenseView.front_photo
      ? licenseView.front_photo.startsWith('http')
        ? licenseView.front_photo
        : // add 'storage/' only if not already present in the URL
          `${baseUrl}${licenseView.front_photo.replace(/^storage\//, 'storage/')}`
      : ''
  let content: JSX.Element | null = null
  switch (contentType) {
    case 'view':
      content = <AttachmentViewer id={id} form_code='frm-GZL' onClose={closeModal} />
      break
    case 'driver_attachments':
      content = (
        <AttachmentViewer
          id={licenseView?.driver_id || 0} // 👈 use driver_id from your licenseView record
          form_code='frm-DRV' // 👈 your driver form code (example)
          onClose={closeModal}
        />
      )
      break
    default:
      content = null
  }
  // Loading
  if (loading) {
    return (
      <Modal show={true} onHide={onClose}>
        <Modal.Header closeButton>
          <Modal.Title>{t('gzlicense.loadingDetails')}</Modal.Title>
        </Modal.Header>
        <Modal.Body className='text-center'>
          <div className='spinner-border' role='status'>
            <span className='visually-hidden'>Loading...</span>
          </div>
          <p className='mt-2'>{t('global.loading')}</p>
        </Modal.Body>
      </Modal>
    )
  }

  // Error
  if (error) {
    return (
      <Modal show={true} onHide={onClose}>
        <Modal.Header closeButton>
          <Modal.Title>{t('gzlicense.errorTitle')}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <p>{error}</p>
        </Modal.Body>
        <Modal.Footer>
          <Button variant='danger' onClick={onClose}>
            {t('global.close')}
          </Button>
        </Modal.Footer>
      </Modal>
    )
  }

  // No Data
  if (!licenseView) {
    return (
      <Modal show={true} onHide={onClose}>
        <Modal.Header closeButton>
          <Modal.Title>{t('gzlicense.noData')}</Modal.Title>
        </Modal.Header>
        <Modal.Body>
          <p>{t('gzlicense.noDataAvailable')}</p>
        </Modal.Body>
        <Modal.Footer>
          <Button variant='danger' onClick={onClose}>
            {t('global.close')}
          </Button>
        </Modal.Footer>
      </Modal>
    )
  }

  return (
    <>
      <Modal show={true} onHide={onClose} size='xl' backdrop='static'>
        <Modal.Header>
          <Modal.Title>{t('global.view', {name: t('gzlicense.license')})}</Modal.Title>
          <div className='d-flex'>
            <button
              className='btn btn-sm btn-flex btn-primary fw-bold me-2'
              onClick={() => openModal('view')}
            >
              <i className='fas fa-paperclip fs-5 me-2'></i>
              {t('gzlicense.viewAttachments')}
            </button>
            <button
              className='btn btn-sm btn-flex btn-info fw-bold'
              onClick={() => openModal('driver_attachments')}
            >
              <i className='fas fa-id-badge fs-5 me-2'></i>
              {t('driver.viewAttachments')}
            </button>
          </div>
        </Modal.Header>

        <Modal.Body>
          {/* Photos Section */}
          <Row className='text-center justify-content-center my-4'>
            {licenseView.driver_photo && (
              <Col md={4} className='text-center'>
                <img
                  src={licenseView.driver_photo}
                  alt='Driver'
                  className='img-thumbnail mb-2'
                  style={{width: '150px', height: '150px', objectFit: 'cover', borderRadius: '8px'}}
                />
                <div>
                  <strong>{t('gzlicense.driver')}:</strong> {licenseView.driver_name}
                </div>
              </Col>
            )}
            {licenseView.front_photo && (
              <Col md={4}>
                <img
                  src={frontPhotoUrl}
                  alt='Vehicle Front'
                  className='img-thumbnail mb-2'
                  style={{width: '150px', height: '150px', objectFit: 'cover', borderRadius: '8px'}}
                />
                <div>
                  <strong>{t('gzlicense.vehiclePhoto')}</strong>
                </div>
              </Col>
            )}
            {licenseView.back_photo && (
              <Col md={4}>
                <img
                  src={
                    licenseView.back_photo.startsWith('http')
                      ? licenseView.back_photo
                      : `http://localhost/GreenZone/APP/${licenseView.back_photo}`
                  }
                  alt='Vehicle Front'
                  className='img-thumbnail mb-2'
                  style={{width: '150px', height: '150px', objectFit: 'cover', borderRadius: '8px'}}
                />
                <div>
                  <strong>{t('gzlicense.back_photo')}</strong>
                </div>
              </Col>
            )}
          </Row>

          {/* Record Owner */}
          <RecordOwnerView
            title={t('global.recordOwner')}
            icon='fa fa-user-plus'
            ownerName={licenseView.created_by}
            departmentName={licenseView.createdDepartment}
            province={licenseView.createdLocation}
            created_at={licenseView.created_at}
          />

          {/* License Info */}
          <div className='record-owner-view mb-4'>
            <div className='row'>
              {[
                {
                  icon: 'fa fa-id-card',
                  label: t('gzlicense.license_type'),
                  value:
                    licenseView.license_type === 'new'
                      ? t('printedCard.new')
                      : licenseView.license_type === 'extend'
                      ? t('printedCard.extend')
                      : licenseView.license_type === 'renew'
                      ? t('printedCard.renew')
                      : licenseView.license_type,
                },
                {
                  icon: 'fa fa-hashtag',
                  label: t('gzlicense.sn'),
                  value: licenseView.sn,
                },
                {
                  icon: 'fa fa-calendar-alt',
                  label: t('gzlicense.issue_date'),
                  value: licenseView.issue_date,
                },
                {
                  icon: 'fa fa-calendar-check',
                  label: t('gzlicense.expire_date'),
                  value: licenseView.expire_date,
                },
              ].map(({icon, label, value}, idx) => (
                <div key={idx} className='col-lg-3 col-md-6 col-sm-12 mb-3'>
                  <div
                    className='p-3 border rounded bg-light h-100 d-flex align-items-center'
                    style={{gap: '0.75rem'}}
                  >
                    <i className={`${icon} text-primary fs-4`} aria-hidden='true' />
                    <div>
                      <label
                        className='form-label text-muted mb-1 fw-bold'
                        style={{fontSize: '1.05rem'}}
                      >
                        {label}:
                      </label>
                      <div className='fs-5 text-dark' style={{wordBreak: 'break-word'}}>
                        {value || <em>{t('global.notAvailable')}</em>}
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Vehicle Info */}
          <div className='record-owner-view mb-4'>
            <div className='row'>
              {[
                {
                  icon: 'fa fa-car',
                  label: t('vehicle.vehicle_type'),
                  value: licenseView.vehicle_type_name,
                },
                {
                  icon: 'fa fa-palette',
                  label: t('vehicle.vehicle_color'),
                  value: licenseView.vehicle_color,
                },
                {
                  icon: 'fa fa-id-badge',
                  label: t('vehicle.vehicle_platte_no'),
                  value: licenseView.vehicle_platte_no
                    ? (() => {
                        const hasMinus = licenseView.vehicle_platte_no.includes('-')
                        const cleaned = licenseView.vehicle_platte_no.replace(/-/g, '').trim()
                        const parts = cleaned.split(' ')
                        if (parts.length < 2) return licenseView.vehicle_platte_no
                        return hasMinus ? `${parts[1]} ${parts[0]}-` : `${parts[1]} ${parts[0]}`
                      })()
                    : '',
                },
              ].map(({icon, label, value}, idx) => (
                <div key={idx} className='col-lg-4 col-md-6 col-sm-12 mb-3'>
                  <div
                    className='p-3 border rounded bg-light h-100 d-flex align-items-center'
                    style={{gap: '0.75rem'}}
                  >
                    <i className={`${icon} text-primary fs-4`} aria-hidden='true' />
                    <div>
                      <label
                        className='form-label text-muted mb-1 fw-bold'
                        style={{fontSize: '1.05rem'}}
                      >
                        {label}:
                      </label>
                      <div className='fs-5 text-dark' style={{wordBreak: 'break-word'}}>
                        {value || <em>{t('global.notAvailable')}</em>}
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>

          {/* Driver Info */}
          <div className='record-owner-view mb-4'>
            <div className='row'>
              {[
                {icon: 'fa fa-user', label: t('driver.f_name'), value: licenseView.driver_f_name},
                {
                  icon: 'fa fa-user',
                  label: t('driver.g_f_name'),
                  value: licenseView.driver_g_f_name,
                },
                {icon: 'fa fa-id-card-alt', label: t('driver.nic'), value: licenseView.driver_nic},
                {icon: 'fa fa-phone', label: t('driver.phone'), value: licenseView.driver_phone},
                {
                  icon: 'fa fa-map-marker-alt',
                  label: t('driver.main_residence'),
                  value: [
                    licenseView.main_province_name,
                    licenseView.main_district_name,
                    licenseView.main_village,
                  ]
                    .filter(Boolean)
                    .join('، '),
                },
                {
                  icon: 'fa fa-map-marker',
                  label: t('driver.current_residence'),
                  value: [
                    licenseView.current_province_name,
                    licenseView.current_district_name,
                    licenseView.current_village,
                  ]
                    .filter(Boolean)
                    .join('، '),
                },
              ].map(({icon, label, value}, idx) =>
                value ? (
                  <div key={idx} className='col-lg-6 col-md-6 col-sm-12 mb-3'>
                    <div
                      className='p-3 border rounded bg-light h-100 d-flex align-items-center'
                      style={{gap: '0.75rem'}}
                    >
                      <i className={`${icon} text-primary fs-4`} aria-hidden='true' />
                      <div>
                        <label
                          className='form-label text-muted mb-1 fw-bold'
                          style={{fontSize: '1.05rem'}}
                        >
                          {label}:
                        </label>
                        <div className='fs-5 text-dark' style={{wordBreak: 'break-word'}}>
                          {value || <em>{t('global.notAvailable')}</em>}
                        </div>
                      </div>
                    </div>
                  </div>
                ) : null
              )}
            </div>
          </div>
        </Modal.Body>
        <Modal.Footer>
          <Button variant='danger' onClick={onClose}>
            {t('global.close')}
          </Button>
        </Modal.Footer>
      </Modal>
      <CustomModal
        modalContent={content}
        show={isModalOpen}
        onClose={closeModal}
        modalSize='lg'
        modalTile={t('global.viewAttachment')}
      />
    </>
  )
}

export default ViewPrintedCard
