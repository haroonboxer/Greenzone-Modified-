import {Fragment, useState} from 'react'
import {useTranslation} from 'react-i18next'
import DataTable from './DataTable'
import {useAuth} from 'app/modules/auth'
import DriverCreateModal from '../create/DriverCreateModal'

const DriverList = () => {
  const [showCreateModal, setShowCreateModal] = useState(false)
  const {t} = useTranslation()
  const {hasPermission} = useAuth()
  const [refreshKey, setRefreshKey] = useState(0)
  const [hasActiveDriver, setHasActiveDriver] = useState(false)

  // callback from DataTable
  // const handleDataChange = (drivers: any[]) => {
  //   setHasActiveDriver(drivers.some((driver) => driver.status === 1))
  // }
  const handleDataChange = (drivers: any[], hasActive?: boolean) => {
    setHasActiveDriver(!!hasActive)
  }

  return (
    <Fragment>
      <div
        className='card mb-5 mb-xl-10 shadow-lg p-3 mb-5 bg-body rounded'
        id='kt_profile_details_view'
      >
        <div className='card-header cursor-pointer'>
          <div className='card-title m-0'>
            <h3 className='fw-bolder m-0'>
              <i className='fas fa-list fs-4 text-primary'></i>&nbsp;
              {t('global.list', {name: t('driver.drivers')})}
            </h3>
          </div>
          <div>
            <div className='d-none d-lg-flex mt-5'>
              <div className='d-flex align-items-center'>
                {hasPermission('driver-create') && !hasActiveDriver && (
                  <button
                    className='btn btn-sm btn-flex btn-primary fw-bolder'
                    onClick={() => setShowCreateModal(true)}
                  >
                    <i className='fa-solid fa-plus'></i>
                    {t('global.add', {name: t('driver.driver')})}
                  </button>
                )}
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
              </div>
            </div>
          </div>
        </div>
        <div className='card-body p-9 table-responsive'>
          <DataTable
            key={refreshKey}
            headers={[
              {headerName: `${t('global.URN')}`, sort: 'boss.id'},
              {headerName: `${t('driver.name')}`, sort: 'driver.name'},
              {headerName: `${t('driver.f_name')}`, sort: 'driver.f_name'},
              {headerName: `${t('driver.phone')}`, sort: 'driver.phone'},
              {headerName: `${t('driver.photo')}`, sort: 'driver.photo'},
              {headerName: `${t('driver.RECORDOWNER')}`, sort: 'driver.RECORDOWNER'},
              {headerName: `${t('driver.status')}`, sort: 'driver.status'},
              {headerName: 'عمل', sort: ''},
            ]}
            columns={[
              'driver.id',
              'driver.name',
              'driver.f_name',
              'driver.phone',
              'driver.photo',
              'driver.RECORDOWNER',
              'driver.status',
              'driver.action',
            ]}
            onDataChange={handleDataChange}
          />
        </div>
      </div>

      <DriverCreateModal
        show={showCreateModal}
        onHide={() => setShowCreateModal(false)}
        onSuccess={() => setRefreshKey((prevKey) => prevKey + 1)}
      />
    </Fragment>
  )
}

export default DriverList
