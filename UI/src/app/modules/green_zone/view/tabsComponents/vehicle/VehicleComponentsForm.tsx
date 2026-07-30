const VehicleComponentsForm = ({t, vehicleData, to_jalali, RecordOwnerView}: any) => {
  return (
    <div className='card' id='kt_profile_details_view'>
      <div className='card-header cursor-pointer'>
        <div className='card-title d-flex justify-content-between align-items-center m-0 flex-row-reverse'>
          <h3 className='fw-bolder m-0'>
            <i className='fas fa-list fs-3 text-primary ms-2'></i>&nbsp;&nbsp;
            {t('global.view', {name: t('vehicle.vehicle')})}
          </h3>
        </div>
      </div>
      <div className='card-body'>
        <RecordOwnerView
          title={t('global.recordOwner')}
          icon={'fa fa-user-plus'}
          ownerName={vehicleData.ownerName}
          departmentName={vehicleData.createdDepartment}
          province={vehicleData.createdLocation}
          created_at={to_jalali(vehicleData.created_at, true)}
        />

        {/* Vehicle Information */}
        <div className='row mt-5 gx-4 gy-4'>
          {[
            {
              icon: 'fa fa-car',
              label: t('vehicle.vehicle_type'),
              value: vehicleData.vehicle_type_name,
            },
            {
              icon: 'fa fa-paint-brush',
              label: t('vehicle.vehicle_color'),
              value: vehicleData.vehicle_color,
            },
            {
              icon: 'fa fa-id-card',
              label: t('vehicle.vehicle_platte_no'),
              value: vehicleData.vehicle_platte_no
                ? (() => {
                    const plate = vehicleData.vehicle_platte_no
                    const hasMinus = plate.includes('-')
                    const cleaned = plate.replace(/-/g, '').trim()
                    const parts = cleaned.split(' ')
                    if (parts.length < 2) return plate
                    return hasMinus ? `${parts[1]} ${parts[0]}-` : `${parts[1]} ${parts[0]}`
                  })()
                : '',
            },
            {
              icon: 'fa fa-cogs',
              label: t('vehicle.vehicle_engine_no'),
              value: vehicleData.vehicle_engine_no,
            },
          ].map(({icon, label, value}, idx) => (
            <div key={idx} className='col-lg-3 col-md-6 col-sm-12'>
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

        {/* Vehicle Photos (unchanged) */}
        <div className='row mt-5 gx-4 gy-4'>
          {[
            {label: t('vehicle.front_photo'), src: vehicleData.front_photo, alt: 'Front Photo'},
            {label: t('vehicle.plate_photo'), src: vehicleData.plate_photo, alt: 'Plate Photo'},
            {label: t('vehicle.back_photo'), src: vehicleData.back_photo, alt: 'Back Photo'},
          ].map(({label, src, alt}, idx) => (
            <div
              key={idx}
              className='col-lg-4 col-md-6 col-sm-12 d-flex flex-column align-items-center'
            >
              <label className='form-label fw-bold mb-3' style={{fontSize: '1.1rem'}}>
                {label}:
              </label>
              {src ? (
                <div
                  className='d-flex justify-content-center align-items-center rounded shadow-sm border'
                  style={{
                    width: '100%',
                    maxWidth: '320px',
                    height: '220px',
                    overflow: 'hidden',
                    backgroundColor: '#f8f9fa',
                  }}
                >
                  <img
                    src={src}
                    alt={alt}
                    style={{maxHeight: '100%', maxWidth: '100%', objectFit: 'contain'}}
                    className='rounded'
                  />
                </div>
              ) : (
                <div
                  className='d-flex justify-content-center align-items-center rounded border text-muted fst-italic'
                  style={{
                    width: '100%',
                    maxWidth: '320px',
                    height: '220px',
                    backgroundColor: '#fafafa',
                  }}
                >
                  {t('global.noPhoto')}
                </div>
              )}
            </div>
          ))}
        </div>
      </div>
    </div>
  )
}

export default VehicleComponentsForm
