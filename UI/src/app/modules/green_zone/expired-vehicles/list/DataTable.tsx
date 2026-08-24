import React, {useEffect, useState, useRef, useMemo} from 'react'
import {debounce} from 'lodash'
import {Dropdown, DropdownButton} from 'react-bootstrap'
import {Link} from 'react-router-dom'
import {useTranslation} from 'react-i18next'
import {useAppDispatch, useAppSelector} from 'redux/hooks'
import Paginator from 'app/customes/Paginator'
import UnAuthorized from 'app/customes/UnAuthorized'
import {encryptId} from 'helpers/EncryptAndDecrypt'
import '../../../../../_metronic/assets/css/dataTable.css'
import Loader from 'app/pages/loading/Loader'
import {useAuth} from 'app/modules/auth'
import {getExpiredVehicles} from 'redux/green_zone/vehicles/VehicleSlice'
// import VehicleEditModal from '../edit/VehicleEditModal'

const SORT_ASC = 'asc'
const SORT_DESC = 'desc'

const DataTable: React.FC<any> = ({headers, columns, onRecordsChange}) => {
  const [data, setData] = useState<any[]>([])
  const [perPage, setPerPage] = useState<number>(10)
  const [sortColumn, setSortColumn] = useState<string>(columns[0])
  const [sortOrder, setSortOrder] = useState<string>(SORT_DESC)
  const [vehicleType, setVehicleType] = useState<string>('')
  const [plateNo, setPlateNo] = useState<string>('')
  const [engineNo, setEngineNo] = useState<string>('')
  const [isAuthorized, setIsAuthorized] = useState<boolean>(true)
  const [pagination, setPagination] = useState<any>({})
  const [currentPage, setCurrentPage] = useState<number>(1)
  const [loading, setLoading] = useState<boolean>(true)
  const {t} = useTranslation()
  const dispatch = useAppDispatch()
  const {vehicleIndex} = useAppSelector((state) => state.vehicle)
  const [showEditModal, setShowEditModal] = useState<boolean>(false)
  const [selectedVehicle, setSelectedVehicle] = useState<any>(null)
  const {hasPermission} = useAuth()

  const params = useMemo(
    () => ({
      sort_field: sortColumn,
      sort_order: sortOrder,
      per_page: perPage,
      page: currentPage,
      vehicle_type: vehicleType,
      vehicle_platte_no: plateNo,
      vehicle_engine_no: engineNo,
    }),
    [sortColumn, sortOrder, perPage, currentPage, vehicleType, plateNo, engineNo]
  )

  const handleSort = (column: string) => {
    setLoading(true)
    if (column === sortColumn) {
      setSortOrder((prevSortOrder) => (prevSortOrder === SORT_ASC ? SORT_DESC : SORT_ASC))
    } else {
      setSortColumn(column)
      setSortOrder(SORT_ASC)
    }
  }

  const handleSearchVehicleType = useRef(
    debounce((query) => {
      setVehicleType(query)
      setCurrentPage(1)
    }, 500)
  ).current

  const handleSearchPlateNo = useRef(
    debounce((query) => {
      setPlateNo(query)
      setCurrentPage(1)
    }, 500)
  ).current

  const handleSearchEngineNo = useRef(
    debounce((query) => {
      setEngineNo(query)
      setCurrentPage(1)
    }, 500)
  ).current

  const handlePerPage = (newPerPage: number) => {
    setPerPage(newPerPage)
    setCurrentPage(1)
  }

  useEffect(() => {
    setLoading(true)
    dispatch(getExpiredVehicles(params)).then((res) => {
     
      if (res.meta.requestStatus === 'fulfilled') {
        setLoading(false)
      } else if (res.meta.requestStatus === 'rejected') {
        setIsAuthorized(false)
        setLoading(false)
      }
    })
  }, [params, dispatch])

  useEffect(() => {
    setData(vehicleIndex.data || [])
    setPagination(vehicleIndex.meta || {})
  }, [vehicleIndex, dispatch, onRecordsChange])

  const memoizedData = useMemo(() => data, [data])
  const memoizedLoading = useMemo(() => loading, [loading])

  return (
    <div>
      {isAuthorized ? (
        <>
          <div className='form collapse' id='movementSearch'>
            <div className='row mb-3'>
              <div className='row mb-8 col-lg-12'>
                <div className='col-lg-3 col-md-5 col-sm-4'>
                  <input
                    type='search'
                    placeholder={t('global.vehicleNameBased')}
                    className='form-control form-control-sm'
                    onChange={(e) => handleSearchVehicleType(e.target.value)}
                  />
                </div>
                <div className='col-lg-3 col-md-5 col-sm-4'>
                  <input
                    type='search'
                    placeholder={t('global.PlateNoBased')}
                    className='form-control form-control-sm'
                    onChange={(e) => handleSearchPlateNo(e.target.value)}
                  />
                </div>
                <div className='col-lg-3 col-md-5 col-sm-4'>
                  <input
                    type='search'
                    placeholder={t('global.EngineNoBased')}
                    className='form-control form-control-sm'
                    onChange={(e) => handleSearchEngineNo(e.target.value)}
                  />
                </div>
                <div className='col-lg-3 col-md-5 col-sm-4'>
                  <div className='input-group'>
                    <label className='mt-2 me-2'>
                      {t('global.recordsPerPage', 'تعداد ریکارد صفحه')}
                    </label>
                    <select
                      className='form-select form-select-sm'
                      value={perPage}
                      onChange={(e) => handlePerPage(Number(e.target.value))}
                    >
                      <option value={5}>5</option>
                      <option value={10}>10</option>
                      <option value={20}>20</option>
                      <option value={50}>50</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div
            className='tableFixHead table-responsive'
            style={{overflowX: 'auto', whiteSpace: 'nowrap'}}
            dir='rtl'
          >
            <table className='table table-hover table-striped'>
              <thead>
                <tr>
                  {headers.map((header: any) => (
                    <th
                      key={header.headerName}
                      onClick={() => handleSort(header.sort)}
                      className='fs-6 fw-bold'
                    >
                      {header.headerName.toUpperCase().replace('_', ' ')}
                      {header.sort === sortColumn && (
                        <i
                          className={`ms-1 fa fa-arrow-${
                            sortOrder === SORT_ASC ? 'up' : 'down'
                          } text-white`}
                        />
                      )}
                    </th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {!memoizedLoading &&
                  memoizedData.map((item, index) => (
                    <tr key={index} className='fs-5'>
                      <td className='fw-bolder' style={{width: '5%', textAlign: 'center'}}>
                        {item.id}
                      </td>
                      <td style={{textAlign: 'center'}}>
                        <Link
                          to={'/green-zone/view/' + encryptId(item.id)}
                          className='text-decoration-none text-blue'
                          style={{fontWeight: 'bold'}}
                        >
                          {item.vehicle_type_name}
                        </Link>
                      </td>
                      <td style={{textAlign: 'center'}}>{item.vehicle_color}</td>
                      <td style={{textAlign: 'center'}}>
                        {item.vehicle_platte_no
                          ? (() => {
                              const hasMinus = item.vehicle_platte_no.includes('-')
                              const cleaned = item.vehicle_platte_no.replace(/-/g, '').trim()
                              const parts = cleaned.split(' ')
                              if (parts.length < 2) return item.vehicle_platte_no
                              return hasMinus
                                ? `${parts[1]} ${parts[0]}-`
                                : `${parts[1]} ${parts[0]}`
                            })()
                          : ''}
                      </td>
                      <td style={{textAlign: 'center'}}>{item.vehicle_engine_no}</td>
                      <td style={{textAlign: 'center'}}>{item.vehicle_source}</td>
                      <td style={{textAlign: 'center', width: '10%'}}>
                        <span>{item.ownerName}</span>
                      </td>
                      <td style={{textAlign: 'center', width: '10%'}}>
                        <DropdownButton
                          id='dropdown-item-button'
                          size='sm'
                          title={<i className='fas fa-ellipsis-v text-muted'></i>}
                          className='dropdown-button-custom'
                        >
                          {hasPermission('vehicle-view') && (
                            <Dropdown.Item as='button' className='dropdown-item-custom'>
                              <Link
                                to={'/green-zone/view/' + encryptId(item.id)}
                                className='text-decoration-none text-dark'
                                style={{fontSize: '0.875rem'}}
                              >
                                <i className='fas fa-eye text-primary me-2'></i>
                                {t('global.view', {name: t('vehicle.vehicle')})}
                              </Link>
                            </Dropdown.Item>
                          )}
                          {hasPermission('vehicle-edit') && (
                            <Dropdown.Item as='button' className='dropdown-item-custom'>
                              <Link
                                to='#'
                                onClick={() => {
                                  setSelectedVehicle(item)
                                  setShowEditModal(true)
                                }}
                                className='text-decoration-none text-dark'
                                style={{fontSize: '0.875rem'}}
                              >
                                <i className='fas fa-edit text-warning me-2'></i>
                                {t('global.edit', {name: t('vehicle.vehicle')})}
                              </Link>
                            </Dropdown.Item>
                          )}
                        </DropdownButton>
                      </td>
                    </tr>
                  ))}
                {memoizedData.length === 0 && !memoizedLoading && (
                  <tr>
                    <td colSpan={8}>
                      <p className='fs-2 text-center text-danger fw-bolder'>
                        {t('global.noRecordFound')}
                      </p>
                    </td>
                  </tr>
                )}
                {memoizedLoading && (
                  <tr>
                    <td colSpan={8}>
                      <Loader />
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>

          {/* {showEditModal && selectedVehicle && (
            <VehicleEditModal
              key={selectedVehicle.id} // this forces full remount
              showModal={showEditModal}
              handleClose={() => {
                setShowEditModal(false)
                setSelectedVehicle(null)
                dispatch(getVehicle(params))
              }}
              vehicleData={selectedVehicle}
            />
          )} */}

          {!memoizedLoading && data.length > 0 && (
            <Paginator
              pagination={pagination}
              pageChanged={setCurrentPage}
              totalItems={data.length}
            />
          )}
        </>
      ) : (
        <UnAuthorized />
      )}
    </div>
  )
}

export default DataTable
