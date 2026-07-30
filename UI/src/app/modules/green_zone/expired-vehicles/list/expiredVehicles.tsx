import {Fragment, useState} from 'react'
import {Link} from 'react-router-dom'
import DataTable from './DataTable'
import {useTranslation} from 'react-i18next'
import {Dropdown, DropdownButton} from 'react-bootstrap'
import {useAuth} from 'app/modules/auth'

const ExpiredVehicles = () => {
  const {t} = useTranslation()
  const [showModal, setShowModal] = useState<boolean>(false)
  const {hasPermission} = useAuth()
  const handleOpenModal = (event: React.MouseEvent) => {
    event.preventDefault()
    setShowModal(true)
  }
  const [refreshKey, setRefreshKey] = useState(0)

  return (
    <Fragment>
      <div
        className='card mb-5 mb-xl-10 shadow-lg p-3 mb-5 bg-body rounded'
        id='kt_profile_details_view'
      >
        <div className='card-header cursor-pointer'>
          <div className='card-title m-0'>
            <h3 className='fw-bolder m-0'>
              <i className='fas fa-list fs-4 text-primary'></i>{' '}
              {t('global.list', {name: t('vehicle.expiredVehicles')})}
            </h3>
          </div>
          <div>
            <div className='d-none d-lg-flex mt-5'>
              <div className='d-flex align-items-center'>
                <div className='me-2 ms-2'>
                  <button
                    className='btn btn-sm btn-flex btn-primary fw-bolder'
                    data-bs-toggle='collapse'
                    data-bs-target='#movementSearch'
                    aria-expanded='true'
                    aria-controls='movementSearch'
                  >
                    <span className='svg-icon svg-icon-5 svg-icon-gray-500 me-1'>
                      <i className='fa-solid fa-arrow-down-short-wide'></i>
                    </span>
                    {t('global.search')}
                  </button>
                </div>

                <Link className='btn btn-sm btn-flex btn-danger fw-bold' to='/dashboard'>
                  <b>
                    <i className='fa-solid fa-reply-all'></i>
                  </b>
                </Link>
              </div>
            </div>
            <div className='dropdown d-lg-none mt-5'>
              <DropdownButton
                id='dropdown-item-button'
                size='sm'
                title={<i className='fas fa-ellipsis-v fw-bold fs-3'></i>}
              >
                <>
                  <Dropdown.Item as='button'>
                    <Link className='fw-bolder text-primary' to={'/authentication/create-user'}>
                      <i className='fa-solid fa-plus  text-primary me-2'></i>

                      {t('global.add', {name: t('global.user')})}
                    </Link>
                  </Dropdown.Item>
                  <Dropdown.Item as='button'>
                    <span
                      className='fw-bolder text-primary'
                      data-bs-toggle='collapse'
                      data-bs-target='#movementSearch'
                      aria-expanded='true'
                      aria-controls='movementSearch'
                    >
                      <span className='svg-icon svg-icon-5 svg-icon-gray-500 me-1'>
                        <i className='fa-solid fa-arrow-down-short-wide text-primary'></i>
                      </span>
                      {t('global.search')}
                    </span>
                  </Dropdown.Item>

                  <Dropdown.Item as='button'>
                    <Link className='fw-bold' to='/dashboard'>
                      <b>
                        <i className='fa-solid fa-reply-all text-danger'></i>
                      </b>
                    </Link>
                  </Dropdown.Item>
                </>
              </DropdownButton>
            </div>
          </div>
        </div>
        <div className='card-body p-9 table-responsive'>
          <DataTable
            key={refreshKey}
            headers={[
              {
                headerName: `${t('global.URN')}`,
                sort: 'vehicle.id',
              },
              {
                headerName: `${t('vehicle.vehicle_type')}`,
                sort: 'vehicle.vehicle_type',
              },
              {
                headerName: `${t('vehicle.vehicle_color')}`,
                sort: 'vehicle.vehicle_color',
              },
              {
                headerName: `${t('vehicle.vehicle_platte_no')}`,
                sort: 'vehicle.vehicle_platte_no',
              },
              {
                headerName: `${t('vehicle.vehicle_engine_no')}`,
                sort: 'vehicle.vehicle_engine_no',
              },
              {
                headerName: `${t('vehicle.vehicle_source')}`,
                sort: 'vehicle.vehicle_source',
              },
              {
                headerName: `${t('global.RECORDOWNER')}`,
                sort: 'vehicle.created_by',
              },
              {
                headerName: 'عمل',
                sort: '',
              },
            ]}
            columns={[
              'vehicle.id',
              'vehicle.vehicle_type',
              'vehicle.vehicle_color',
              'vehicle.vehicle_platte_no',
              'vehicle.vehicle_engine_no',
              'vehicle.vehicle_source',
              'vehicle.created_by',
            ]}
          />
        </div>
      </div>
    </Fragment>
  )
}

export default ExpiredVehicles
