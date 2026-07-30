import { Modal, Button } from 'react-bootstrap'
import { t } from 'i18next'
import CustomModal from 'app/customes/CustomModal'
import AttachmentViewer from 'app/modules/authentication/components/attachment/view-attachment/AttachmentViewer'
import { useState } from 'react'

interface Props {
  show: boolean
  data: any
  loading: boolean
  onClose: () => void
  to_jalali?: (date: string, withTime?: boolean) => string
}

const DriverViewModal: React.FC<Props> = ({ show, data, loading, onClose }) => {
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

  if (loading) {
    return (
      <Modal show={show} onHide={onClose}>
        <Modal.Header closeButton>
          <Modal.Title>{t('global.loading')}</Modal.Title>
        </Modal.Header>
        <Modal.Body>{t('global.loading')}...</Modal.Body>
      </Modal>
    )
  }

  if (!data) {
    return (
      <Modal show={show} onHide={onClose}>
        <Modal.Header closeButton>
          <Modal.Title>{t('global.noRecordFound')}</Modal.Title>
        </Modal.Header>
        <Modal.Body>{t('global.noDataAvailable')}</Modal.Body>
      </Modal>
    )
  }


  let modalContent: JSX.Element | null = null

  switch (contentType) {
    case 'view':
      modalContent = <AttachmentViewer id={data.id} form_code='frm-DRV' onClose={closeModal} />
      break
    default:
      modalContent = null
  }

  const driver = data

  return (
    <>
      <Modal show={show} onHide={onClose} size='xl' backdrop='static'>
        <Modal.Header>
          <Modal.Title>
            <i className="fas fa-id-card text-primary me-2" />
            {t('global.view', { name: t('driver.driver') })}
          </Modal.Title>

          {/* View Attachment button in place of close */}
          <button
            className="btn btn-sm btn-flex btn-primary fw-bold"
            onClick={() => openModal('view')}
          >
            <i className="fas fa-camera fs-5 me-2"></i>
            {t('global.viewAttachment')}
          </button>
        </Modal.Header>

        <Modal.Body>
          <div className="card" id="kt_profile_details_view">
            <div className="card-body">
              <div className="row">

                {/* Left side - details */}
                <div className="col-md-8">

                  {/* Record Owner Info */}
                  <div className="row gx-4 gy-4">
                    {[
                      { icon: 'fa fa-user-tie', label: t('global.recordOwner'), value: driver.createdBy },
                      { icon: 'fa fa-building', label: t('global.departmentName'), value: driver.createdDepartment },
                      { icon: 'fa fa-map', label: t('global.recordLocation'), value: driver.createdLocation },
                    ].map(({ icon, label, value }, idx) => (
                      <div key={idx} className="col-lg-4 col-md-6 col-sm-12">
                        <div
                          className="p-3 border rounded bg-light h-100 d-flex align-items-center"
                          style={{ gap: '0.75rem' }}
                        >
                          <i className={`${icon} text-primary fs-4`} aria-hidden="true" />
                          <div>
                            <label className="form-label text-muted mb-1 fw-bold" style={{ fontSize: '1.05rem' }}>
                              {label}:
                            </label>
                            <div className="fs-5 text-dark" style={{ wordBreak: 'break-word' }}>
                              {value || <em>{t('global.notAvailable')}</em>}
                            </div>
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>

                  {/* Driver Basic Info */}
                  <div className="row mt-4 gx-4 gy-4">
                    {[
                      { icon: 'fa fa-user', label: t('driver.name'), value: driver.name },
                      { icon: 'fa fa-user', label: t('driver.f_name'), value: driver.f_name },
                      { icon: 'fa fa-user', label: t('driver.g_f_name'), value: driver.g_f_name },
                      { icon: 'fa fa-phone', label: t('driver.phone'), value: driver.phone },
                      { icon: 'fa fa-car', label: t('driver.vehicle_id_name'), value: driver.vehicle_type_name },
                      { icon: 'fa fa-id-card', label: t('driver.nic'), value: driver.nic },
                    ].map(({ icon, label, value }, idx) => (
                      <div key={idx} className="col-lg-4 col-md-6 col-sm-12">
                        <div
                          className="p-3 border rounded bg-light h-100 d-flex align-items-center"
                          style={{ gap: '0.75rem' }}
                        >
                          <i className={`${icon} text-primary fs-4`} aria-hidden="true" />
                          <div>
                            <label className="form-label text-muted mb-1 fw-bold" style={{ fontSize: '1.05rem' }}>
                              {label}:
                            </label>
                            <div className="fs-5 text-dark" style={{ wordBreak: 'break-word' }}>
                              {value || <em>{t('global.notAvailable')}</em>}
                            </div>
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>

                {/* Right side - photo */}
                <div className="col-md-4 d-flex flex-column align-items-center">
                  {driver.photo ? (
                    <div
                      className="d-flex justify-content-center align-items-center rounded shadow-sm border"
                      style={{
                        width: '100%',
                        maxWidth: '300px',
                        height: '245px',
                        overflow: 'hidden',
                        backgroundColor: '#f8f9fa',
                      }}
                    >
                      <img
                        src={driver.photo}
                        alt="Driver Photo"
                        style={{ maxHeight: '100%', maxWidth: '100%', objectFit: 'contain' }}
                        className="rounded"
                      />
                    </div>
                  ) : (
                    <div
                      className="d-flex justify-content-center align-items-center rounded border text-muted fst-italic"
                      style={{
                        width: '100%',
                        maxWidth: '300px',
                        height: '260px',
                        backgroundColor: '#fafafa',
                      }}
                    >
                      {t('global.noPhoto')}
                    </div>
                  )}
                </div>
                {/* Address Info */}
                <div className="row mt-4 gx-4 gy-4">
                  {[
                    { icon: 'fa fa-map', label: t('driver.main_province'), value: driver.mainProvince },
                    { icon: 'fa fa-location', label: t('driver.main_district'), value: driver.mainDistrict },
                    { icon: 'fa fa-location-dot', label: t('driver.main_village'), value: driver.main_village },
                    { icon: 'fa fa-map', label: t('driver.current_province'), value: driver.currentProvince },
                    { icon: 'fa fa-location', label: t('driver.current_district'), value: driver.currentDistrict },
                    { icon: 'fa fa-location-dot', label: t('driver.current_village'), value: driver.current_village },
                  ].map(({ icon, label, value }, idx) => (
                    <div key={idx} className="col-lg-4 col-md-6 col-sm-12">
                      <div
                        className="p-3 border rounded bg-light h-100 d-flex align-items-center"
                        style={{ gap: '0.75rem' }}
                      >
                        <i className={`${icon} text-primary fs-4`} aria-hidden="true" />
                        <div>
                          <label className="form-label text-muted mb-1 fw-bold" style={{ fontSize: '1.05rem' }}>
                            {label}:
                          </label>
                          <div className="fs-5 text-dark">
                            {value || <em>{t('global.notAvailable')}</em>}
                          </div>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            </div>
          </div>
        </Modal.Body>


        <Modal.Footer>
          <Button variant="danger" onClick={onClose}>
            {t('global.close')}
          </Button>
        </Modal.Footer>
      </Modal>

      <CustomModal
        modalContent={modalContent}
        show={isModalOpen}
        onClose={closeModal}
        modalSize='sm'
        modalTile={t('global.viewAttachment')}
      />
    </>
  )
}

export default DriverViewModal
