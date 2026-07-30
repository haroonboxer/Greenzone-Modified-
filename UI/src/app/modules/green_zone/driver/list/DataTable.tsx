import {debounce} from 'lodash'
import Paginator from 'app/customes/Paginator'
import UnAuthorized from 'app/customes/UnAuthorized'
import Loader from 'app/pages/loading/Loader'
import {useEffect, useMemo, useRef, useState} from 'react'
import {Dropdown, DropdownButton, OverlayTrigger, Tooltip} from 'react-bootstrap'
import {useTranslation} from 'react-i18next'
import {Link, useParams} from 'react-router-dom'
import {useAppDispatch, useAppSelector} from 'redux/hooks'
import {useAuth} from 'app/modules/auth'
import {getDriver, viewDriver} from 'redux/green_zone/driver/driverSlice'
import image from '_metronic/assets/images/user_male.png'
import StatusModal from '../status/StatusModal'
import ViewModal from '../view/ViewModal'
import DriverEditModal from '../edit/DriverEditModal'

const SORT_ASC = 'asc'
const SORT_DESC = 'desc'

// interface DataTableProps {
//   headers: any[]
//   columns: string[]
//   onDataChange?: (data: any[]) => void
// }

interface DataTableProps {
  headers: any[]
  columns: string[]
  onDataChange?: (data: any[], hasActiveDriver?: boolean) => void
}

const DriverDataTable: React.FC<DataTableProps> = ({headers, columns, onDataChange}) => {
  const [data, setData] = useState<any[]>([])
  const [perPage, setPerPage] = useState<number>(10)
  const [sortColumn, setSortColumn] = useState(columns[0])
  const [sortOrder, setSortOrder] = useState(SORT_DESC)
  const [currentPage, setCurrentPage] = useState(1)
  const [nameSearch, setNameSearch] = useState('')
  const [fatherNameSearch, setFatherNameSearch] = useState('')
  const [phoneSearch, setPhoneSearch] = useState('')
  const [isAuthorized, setIsAuthorized] = useState(true)
  const [pagination, setPagination] = useState<any>({})
  const [loading, setLoading] = useState(true)
  const [showStatusModal, setShowStatusModal] = useState(false)
  const [showViewModal, setShowViewModal] = useState(false)
  const [viewData, setViewData] = useState<any>(null)
  const [viewLoading, setViewLoading] = useState(false)
  const [showEditModal, setShowEditModal] = useState(false)
  const [selectedDriver, setSelectedDriver] = useState<any>(null)
  const [dismissReason, setDismissReason] = useState<string | null>(null)
  const [showReasonModal, setShowReasonModal] = useState(false)

  const {t} = useTranslation()
  const dispatch = useAppDispatch()
  const {driverIndex} = useAppSelector((state) => state.driver)
  const memoizedData = useMemo(() => data, [data])
  const memoizedLoading = useMemo(() => loading, [loading])
  const {id} = useParams<{id: string}>()
  const {hasPermission} = useAuth()

  const params = useMemo(
    () => ({
      sort_field: sortColumn,
      sort_order: sortOrder,
      per_page: perPage,
      page: currentPage,
      vehicle_id: id,
      name: nameSearch,
      f_name: fatherNameSearch,
      phone: phoneSearch,
    }),
    [sortColumn, sortOrder, perPage, currentPage, id, nameSearch, fatherNameSearch, phoneSearch]
  )

  const handleSort = (column: string) => {
    setLoading(true)
    if (column === sortColumn) {
      setSortOrder(sortOrder === SORT_ASC ? SORT_DESC : SORT_ASC)
    } else {
      setSortColumn(column)
      setSortOrder(SORT_ASC)
    }
  }

  const handleSearchName = useRef(
    debounce((value: string) => {
      setNameSearch(value)
      setCurrentPage(1)
    }, 500)
  ).current

  const handleSearchFatherName = useRef(
    debounce((value: string) => {
      setFatherNameSearch(value)
      setCurrentPage(1)
    }, 500)
  ).current

  const handleSearchPhone = useRef(
    debounce((value: string) => {
      setPhoneSearch(value)
      setCurrentPage(1)
    }, 500)
  ).current

  const handlePerPage = (value: number) => {
    setPerPage(value)
    setCurrentPage(1)
  }

  const handleViewDriver = async (driverId: number) => {
    setViewLoading(true)
    setShowViewModal(true)
    const res = await dispatch(viewDriver({id: driverId}) as any)
    if (res.meta.requestStatus === 'fulfilled') setViewData(res.payload.record)
    setViewLoading(false)
  }

  useEffect(() => {
    setLoading(true)
    dispatch(getDriver(params)).then((res) => {
      if (res.meta.requestStatus === 'fulfilled') setLoading(false)
      else if (res.meta.requestStatus === 'rejected') {
        setIsAuthorized(false)
        setLoading(false)
      }
    })
  }, [params, dispatch])

  // useEffect(() => {
  //   setData(driverIndex.data || [])
  //   setPagination(driverIndex.meta || {})
  //   if (onDataChange) onDataChange(driverIndex.data || [])
  // }, [driverIndex, onDataChange])

  useEffect(() => {
    setData(driverIndex.data || [])
    setPagination(driverIndex.meta || {})

    if (onDataChange) {
      onDataChange(driverIndex.data || [], driverIndex.meta?.has_active_driver)
    }
  }, [driverIndex, onDataChange])

  return (
    <div>
      {isAuthorized ? (
        <>
          <div className='form collapse' id='movementSearch'>
            <div className='row mb-8 col-md-12'>
              <div className='col-md-3'>
                <input
                  type='search'
                  placeholder={t('driver.searchByName', 'جستجو به اساس نام')}
                  className='form-control form-control-sm'
                  onChange={(e) => handleSearchName(e.target.value)}
                />
              </div>
              <div className='col-md-3'>
                <input
                  type='search'
                  placeholder={t('driver.searchByFatherName', 'جستجو به اساس نام پدر')}
                  className='form-control form-control-sm'
                  onChange={(e) => handleSearchFatherName(e.target.value)}
                />
              </div>
              <div className='col-md-3'>
                <input
                  type='search'
                  placeholder={t('driver.searchByPhone', 'جستجو به اساس شماره تلفن')}
                  className='form-control form-control-sm'
                  onChange={(e) => handleSearchPhone(e.target.value)}
                />
              </div>
              <div className='col-md-3' style={{marginTop: '-13px'}}>
                <label>{t('global.recordsPerPage')}</label>
                <br />
                <select
                  className='form-select form-select-sm'
                  value={perPage}
                  onChange={(e) => handlePerPage(Number(e.target.value))}
                >
                  {[5, 10, 20, 50].map((val) => (
                    <option key={val} value={val}>
                      {val}
                    </option>
                  ))}
                </select>
              </div>
            </div>
          </div>

          <div className='table-responsive tableFixHead' dir='rtl'>
            <table className='table table-hover table-striped'>
              <thead>
                <tr>
                  {headers.map((header) => (
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
                    <tr key={index}>
                      <td className='fw-bolder text-center'>{memoizedData.length - index}</td>
                      <td className='text-center'>{item.name}</td>
                      <td className='text-center'>{item.f_name}</td>
                      <td className='text-center'>{item.phone}</td>
                      <td className='text-center'>
                        <img
                          src={item.photo}
                          alt='Driver'
                          className='company-icon'
                          style={{width: 45, height: 45, borderRadius: 10, objectFit: 'contain'}}
                          onError={(e) => (e.currentTarget.src = image)}
                        />
                      </td>
                      <td className='text-center'>{item.ownerName}</td>
                      <td className='text-center'>
                        {item.status === 0 ? (
                          <OverlayTrigger
                            placement='top'
                            overlay={<Tooltip>{t('driver.tooltip')}</Tooltip>}
                          >
                            <span
                              className='badge badge-danger'
                              style={{cursor: 'pointer'}}
                              onClick={() => {
                                setDismissReason(
                                  item.reason_dismissed || t('driver.noReasonProvided')
                                )
                                setShowReasonModal(true)
                              }}
                            >
                              <i className='fas fa-info-circle me-1' style={{color: '#fff'}}></i>{' '}
                              {t('global.deactive')}
                            </span>
                          </OverlayTrigger>
                        ) : item.status === 1 ? (
                          <span className='badge badge-success'>{t('global.active')}</span>
                        ) : null}
                      </td>
                      <td className='text-center'>
                        <DropdownButton
                          id='dropdown-item-button'
                          size='sm'
                          title={<i className='fas fa-ellipsis-v text-muted'></i>}
                          className='dropdown-button-custom'
                        >
                          {hasPermission('driver-view') && (
                            <Dropdown.Item as='button' onClick={() => handleViewDriver(item.id)}>
                              <i className='fas fa-eye text-primary me-2'></i>
                              {t('global.view', {name: t('driver.driver')})}
                            </Dropdown.Item>
                          )}
                          {hasPermission('driver-edit') && (
                            <Dropdown.Item
                              as='button'
                              onClick={() => {
                                setSelectedDriver(item)
                                setShowEditModal(true)
                              }}
                            >
                              <i className='fas fa-edit text-warning me-2'></i>
                              {t('global.edit', {name: t('driver.driver')})}
                            </Dropdown.Item>
                          )}
                          <Dropdown.Item
                            as='button'
                            onClick={() => {
                              setSelectedDriver(item)
                              setShowStatusModal(true)
                            }}
                          >
                            <i className='fas fa-exchange text-danger me-2'></i>
                            {t('global.changeStat', {name: t('driver.driver')})}
                          </Dropdown.Item>
                        </DropdownButton>
                      </td>
                    </tr>
                  ))}
                {memoizedData.length === 0 && !memoizedLoading && (
                  <tr>
                    <td colSpan={8} className='text-center text-danger fw-bolder fs-2'>
                      {t('global.noRecordFound')}
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

          <StatusModal
            showModal={showStatusModal}
            setShowModal={setShowStatusModal}
            onSuccess={() => dispatch(getDriver(params))}
            currentStatus={selectedDriver?.status || 0}
            vehicle_id={selectedDriver?.id || 0}
          />
          <ViewModal
            show={showViewModal}
            data={viewData}
            loading={viewLoading}
            onClose={() => setShowViewModal(false)}
          />
          <DriverEditModal
            show={showEditModal}
            onHide={() => setShowEditModal(false)}
            onSuccess={() => dispatch(getDriver(params))}
            driverId={selectedDriver?.id || null}
          />

          {showReasonModal && (
            <div className='modal fade show' style={{display: 'block'}}>
              <div className='modal-dialog'>
                <div className='modal-content'>
                  <div className='modal-header'>
                    <h5 className='modal-title'>{t('driver.dismissReason')}</h5>
                    <button
                      type='button'
                      className='btn-close'
                      onClick={() => setShowReasonModal(false)}
                    ></button>
                  </div>
                  <div className='modal-body'>
                    <p>{dismissReason}</p>
                  </div>
                  <div className='modal-footer'>
                    <button
                      type='button'
                      className='btn btn-danger'
                      onClick={() => setShowReasonModal(false)}
                    >
                      {t('global.close')}
                    </button>
                  </div>
                </div>
              </div>
            </div>
          )}

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

export default DriverDataTable
