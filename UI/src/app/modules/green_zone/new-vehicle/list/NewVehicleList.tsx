import {Fragment, useState} from 'react'
import {useTranslation} from 'react-i18next'
import DataTable from './Datatable'
import CreateModal from './CreateModal'

const NewVehicleList: React.FC = () => {
  const {t} = useTranslation()
  const [refreshKey, setRefreshKey] = useState(0)
  const [showCreateModal, setShowCreateModal] = useState(false)
  return (
    <Fragment>
      <div
        className='card mb-5 mb-xl-10 shadow-lg p-3 mb-5 bg-body rounded'
        id='kt_profile_details_view'
      >
        <div className='card-header cursor-pointer d-flex justify-content-between align-items-center'>
          <div className='card-title m-0'>
            <h3 className='fw-bolder m-0'>
              <i className='fas fa-list fs-4 text-primary'></i>&nbsp;
              {t('global.list', {name: t('newVehicles.vehicles')})}
            </h3>
          </div>
          {/* =================================================================================================================================== */}
          <div className='d-flex align-items-center'>
            <button
              className='btn btn-sm btn-flex btn-primary fw-bolder'
              onClick={() => setShowCreateModal(true)}
            >
              <i className='fa-solid fa-plus'></i>
              {t('global.add', {name: t('vehicle.vehicleName')})}
            </button>
            <button
              className='btn btn-sm btn-flex btn-primary fw-bolder mx-4'
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
        {/* =================================================================================================================================== */}
        <div className='card-body p-9 table-responsive'>
          <DataTable
            key={refreshKey}
            headers={[
              {headerName: t('global.URN'), sort: 'global.id'},
              {
                headerName: t('newVehicles.vehicle_name'),
                sort: 'printedCards.name',
              },
              {
                headerName: t('global.RECORDOWNER'),
                sort: 'printedCards.created_by',
              },
              {
                headerName: 'عمل',
                sort: 'عمل',
              },
            ]}
            columns={['newVehicles.id', 'newVehicles.name', 'newVehicles.created_by']}
            refresh={refreshKey}
          />
        </div>
      </div>
      {/* =================================================================================================================================== */}
      <CreateModal
        show={showCreateModal}
        onHide={() => setShowCreateModal(false)}
        onSuccess={() => setRefreshKey((prevKey) => prevKey + 1)}
      />
    </Fragment>
  )
}

export default NewVehicleList
