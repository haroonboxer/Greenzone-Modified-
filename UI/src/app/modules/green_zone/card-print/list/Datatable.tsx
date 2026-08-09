import React, {useEffect, useState, useRef, useMemo} from 'react'
import {debounce} from 'lodash'
import {Dropdown, DropdownButton} from 'react-bootstrap'
import {useTranslation} from 'react-i18next'
import {useAppDispatch, useAppSelector} from 'redux/hooks'
import {useParams} from 'react-router-dom'
import {useAuth} from 'app/modules/auth'
import Paginator from 'app/customes/Paginator'
import UnAuthorized from 'app/customes/UnAuthorized'
import Loader from 'app/pages/loading/Loader'
import Swal from 'sweetalert2'
import '../../../../../_metronic/assets/css/dataTable.css'
import {changeStatusOfLicense, getPrintedCard} from 'redux/green_zone/Card/CardPrintSlice'
import ViewPrintedCard from './viewPrintedCard'
import {getVehicleSave} from 'redux/green_zone/vehicleSave/vehicleSaveSlice'
import axios from 'axios'
import {changeStatusOfPrint} from 'redux/green_zone/license/licenseSlice'

const SORT_ASC = 'asc'
const SORT_DESC = 'desc'

interface Header {
  headerName: string
  sort: string
}
interface DataTableProps {
  headers: Header[]
  columns: string[]
  onRecordsChange?: (records: any[]) => void
  refresh?: number
}

const DataTable: React.FC<DataTableProps> = ({headers, columns, onRecordsChange, refresh}) => {
  const [data, setData] = useState<any[]>([])
  const [perPage, setPerPage] = useState<number>(10)
  const [sortColumn, setSortColumn] = useState<string>(columns[0])
  const [sortOrder, setSortOrder] = useState<string>(SORT_DESC)
  const [isAuthorized, setIsAuthorized] = useState<boolean>(true)
  const [pagination, setPagination] = useState<any>({})
  const [currentPage, setCurrentPage] = useState<number>(1)
  const [loading, setLoading] = useState<boolean>(true)
  const [showViewModal, setShowViewModal] = useState<boolean>(false)
  const [selectedId, setSelectedId] = useState<number | null>(null)
  const [searchTerm, setSearchTerm] = useState<string>('')
  const [dataa, setDataa] = useState<boolean>(false)
  const [showRejectModal, setShowRejectModal] = useState<boolean>(false)
  const [rejectReason, setRejectReason] = useState<string>('')
  const [rejectTargetId, setRejectTargetId] = useState<number | null>(null)
  const {id} = useParams<{id: string}>()
  const dispatch = useAppDispatch()
  const {t} = useTranslation()
  const {hasPermission} = useAuth()
  const [vehicleSearch, setVehicleSearch] = useState('')
  const [driverSearch, setDriverSearch] = useState('')
  const [plateSearch, setPlateSearch] = useState('')
  const [vehicleOptions, setVehicleOptions] = useState<any[]>([])
  // ====================================================================================================================================

  const params = useMemo(
    () => ({
      sort_field: sortColumn,
      sort_order: sortOrder,
      per_page: perPage,
      page: currentPage,
      vehicle_id: id,
      vehicle_name: vehicleSearch,
      driver_name: driverSearch,
      plate_no: plateSearch,
    }),
    [sortColumn, sortOrder, perPage, currentPage, id, vehicleSearch, driverSearch, plateSearch]
  )

  const handleVehicleSearch = useRef(
    debounce((query: string) => {
      setVehicleSearch(query)
      setCurrentPage(1)
    }, 500)
  ).current

  const handleDriverSearch = useRef(
    debounce((query: string) => {
      setDriverSearch(query)
      setCurrentPage(1)
    }, 500)
  ).current

  const handlePlateSearch = useRef(
    debounce((query: string) => {
      setPlateSearch(query)
      setCurrentPage(1)
    }, 500)
  ).current

  const handleSort = (column: string) => {
    setSortOrder((prev) =>
      column === sortColumn ? (prev === SORT_ASC ? SORT_DESC : SORT_ASC) : SORT_ASC
    )
    setSortColumn(column)
  }

  const handlePerPage = (newPerPage: number) => {
    setPerPage(newPerPage)
    setCurrentPage(1)
  }
  // ====================================================================================================================================
  useEffect(() => {
  
    dispatch(getVehicleSave({per_page: 100})) // or your API params
      .then((res: any) => {
        if (res.meta?.requestStatus === 'fulfilled') {
          setVehicleOptions(res.payload?.data || [])
        }
      })
  }, [dispatch])

  // ====================================================================================================================================
  useEffect(() => {
    setLoading(true)
    dispatch(getPrintedCard(params)).then((res: any) => {
      if (res.meta?.requestStatus === 'fulfilled') {
        setData(res.payload?.data || [])
        setPagination(res.payload?.meta || {})
        onRecordsChange?.(res.payload?.data || [])
      } else {
        setIsAuthorized(false)
      }
      setLoading(false)
    })
  }, [params, dispatch, onRecordsChange, dataa, refresh])
  // ====================================================================================================================================
  const handleAfterPrint = async (wid: number) => {
    const result = await Swal.fire({
      title: 'تایید چاپ کارت',
      text: 'آیا کارت درست چاپ شده است؟',
      icon: 'question',
      confirmButtonText: 'بلی',
      cancelButtonText: 'نخیر',
      showCancelButton: true,
      allowOutsideClick: false,
    })
    const status = result.isConfirmed ? 1 : 0
    try {
      await dispatch(changeStatusOfPrint({id: wid, status})).unwrap()
      Swal.fire({
        title: result.isConfirmed ? 'تایید شد' : 'رد شد',
        icon: result.isConfirmed ? 'success' : 'info',
        timer: 3000,
      })
      setDataa((prev) => !prev)
    } catch {
      Swal.fire({title: 'خطا', text: 'ثبت وضعیت با خطا مواجه شد', icon: 'error'})
    }
  }
  // ====================================================================================================================================
  const handleServerPrint = (wid: number) => {
    axios
      .get(`/api/card/generate-license/${wid}`)
      .then((resp) => {
        const blob = new Blob([resp.data.html], {type: 'text/html'})
        const url = URL.createObjectURL(blob)
        const iframe = document.createElement('iframe')
        iframe.style.display = 'none'
        iframe.src = url
        document.body.appendChild(iframe)
        iframe.contentWindow?.print()
        // iframe.onload = () => setTimeout(() => handleAfterPrint(wid), 0)
      })
      .catch((err) => console.error(err))
  }
  // const handleServerPrint = (wid: number) => {
  //   const url = `http://127.0.0.1:8000/card/print/${wid}`;
  //   window.open(url, '_blank');
  // };

  // ====================================================================================================================================

  return (
    <div>
      {isAuthorized ? (
        <>
          <div className='form collapse' id='movementSearch'>
            <div className='row mb-3 mt-3'>
              <div className='col-lg-3 mb-3'>
                <label style={{fontWeight: 'bold'}}>{t('global.vehicleNameBased')}</label>
                <select
                  className='form-select form-control-sm'
                  style={{height: '40px'}}
                  onChange={(e) => {
                    setVehicleSearch(e.target.value)
                    setCurrentPage(1)
                  }}
                >
                  <option value=''>{t('global.vehicleNameBased')}</option>
                  {vehicleOptions.map((v) => (
                    <option key={v.id} value={v.name}>
                      {v.name}
                    </option>
                  ))}
                </select>
              </div>

              <div className='col-lg-3 mb-3'>
                <label style={{fontWeight: 'bold'}}>{t('global.DriverNameBased')}</label>
                <input
                  type='search'
                  placeholder={t('global.DriverNameBased')}
                  className='form-control form-control-sm'
                  onChange={(e) => handleDriverSearch(e.target.value)}
                />
              </div>
              <div className='col-lg-3 mb-3'>
                <label style={{fontWeight: 'bold'}}>{t('global.PlateNoBased')}</label>
                <input
                  type='search'
                  placeholder={t('global.PlateNoBased')}
                  className='form-control form-control-sm'
                  onChange={(e) => handlePlateSearch(e.target.value)}
                />
              </div>
              <div className='col-lg-3 mb-3'>
                <label style={{fontWeight: 'bold'}}>{t('global.recordsPerPage')}</label>
                <div className='input-group'>
                  <select
                    className='form-select form-control form-select-sm'
                    value={perPage}
                    onChange={(e) => handlePerPage(Number(e.target.value))}
                  >
                    {[5, 10, 20, 50].map((v) => (
                      <option key={v} value={v}>
                        {v}
                      </option>
                    ))}
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div className='table-responsive tableFixHead' dir='rtl'>
            <table className='table table-hover table-striped'>
              <thead className='bg-gray-500'>
                <tr>
                  {headers.map((h) => (
                    <th
                      key={h.headerName}
                      onClick={() => handleSort(h.sort)}
                      className={`fs-6 fw-bold  ${
                        h.headerName === 'عمل' || h.headerName === 'حالت'
                          ? 'text-center'
                          : 'text-right'
                      }`}
                      style={{
                        position: 'sticky',
                        top: 0,
                        backgroundColor: '#153a81',
                        color: 'white',
                        cursor: 'pointer',
                        padding: '10px 20px',
                        textAlign: 'right',
                        paddingRight: '1.5rem',
                        paddingLeft: '1.5rem',
                      }}
                    >
                      {h.headerName.toUpperCase().replace('_', ' ')}
                      {h.sort === sortColumn && (
                        <i
                          className={`ms-1 fa fa-arrow-${
                            sortOrder === SORT_ASC ? 'up' : 'down'
                          } text-white`}
                        ></i>
                      )}
                    </th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {loading && (
                  <tr>
                    <td colSpan={headers.length}>
                      <Loader />
                    </td>
                  </tr>
                )}
                {!loading && data.length === 0 && (
                  <tr>
                    <td colSpan={headers.length}>
                      <p className='fs-2 text-center text-danger fw-bolder'>
                        {t('global.noRecordFound')}
                      </p>
                    </td>
                  </tr>
                )}
                {!loading &&
                  data.map((item, idx) => (
                    <tr key={item.id || idx} className='fs-5'>
                      <td
                        style={{paddingRight: '1rem', paddingLeft: '1rem'}}
                        className='fw-bolder text-right'
                      >
                        {idx + 1}
                      </td>
                      <td
                        style={{paddingRight: '1rem', paddingLeft: '1rem'}}
                        className='text-right'
                      >
                        {item.vehicle.type_name}
                      </td>
                      <td
                        style={{paddingRight: '1rem', paddingLeft: '1rem'}}
                        className='text-right'
                      >
                        {item.driver.name}
                      </td>
                      <td
                        style={{paddingRight: '1rem', paddingLeft: '1rem'}}
                        className='text-right'
                      >
                        {item.vehicle?.plate_no
                          ? (() => {
                              const hasMinus = item.vehicle.plate_no.includes('-')
                              const cleaned = item.vehicle.plate_no.replace(/-/g, '').trim()
                              const parts = cleaned.split(' ')
                              if (parts.length < 2) return item.vehicle.plate_no
                              return hasMinus
                                ? `${parts[1]} ${parts[0]}-`
                                : `${parts[1]} ${parts[0]}`
                            })()
                          : ''}
                      </td>
                      <td
                        style={{paddingRight: '1rem', paddingLeft: '1rem'}}
                        className='fw-bolder text-right'
                      >
                        {item.vehicle.source}
                      </td>
                      <td className='fw-bolder'>
                        {item.license_type === 'new' && (
                          <span style={{color: '#28a745', fontSize: '19px'}}>جدید</span>
                        )}
                        {item.license_type === 'extend' && (
                          <span style={{color: '#007bff', fontSize: '18px'}}>تمدید</span>
                        )}
                        {item.license_type === 'renew' && (
                          <span style={{color: '#3fa307', fontSize: '18px'}}>مثنی</span>
                        )}
                      </td>
                      <td
                        style={{paddingRight: '1rem', paddingLeft: '1rem'}}
                        className='fw-bolder text-right'
                      >
                        {item.created_by.name}
                      </td>
                      <td className='text-center'>
                        {item.status === 0 && (
                          <>
                            <span className='badge badge-warning'>در حال انتظار</span>&nbsp;&nbsp;
                            <span className='badge badge-dark'>چاپ نشده</span>
                          </>
                        )}
                        {item.status === 1 && <span className='badge badge-info'>دریافت شده</span>}
                        {item.status === 2 && (
                          <span className='badge badge-success'>تایید شده</span>
                        )}
                        {item.status === 3 && <span className='badge badge-primary'>جدید</span>}
                        {item.status === 4 && (
                          <>
                            <span className='badge badge-danger'>رد شده</span>
                            <span className='badge badge-danger'>دوباره برسی شود </span>
                          </>
                        )}
                      </td>

                      <td className='text-center'>
                        <DropdownButton
                          id='dropdown'
                          size='sm'
                          title={<i className='fas fa-ellipsis-v text-muted'></i>}
                        >
                          {item.status === 1 && hasPermission('cards-print-accept') && (
                            <Dropdown.Item
                              as='button'
                              onClick={async () => {
                                const swalWithBootstrapButtons = Swal.mixin({
                                  customClass: {
                                    confirmButton: 'btn btn-success me-3',
                                    cancelButton: 'btn btn-danger',
                                  },
                                  buttonsStyling: false,
                                })

                                const result = await swalWithBootstrapButtons.fire({
                                  title: t('printedCard.confirmText'),
                                  text: t('printedCard.confirmSubText'),
                                  icon: 'question',
                                  showCancelButton: true,
                                  confirmButtonText: t('global.yes'),
                                  cancelButtonText: t('global.no'),
                                  reverseButtons: false,
                                })

                                if (result.isConfirmed) {
                                  try {
                                    const formData = new FormData()
                                    formData.append('id', item.id)
                                    formData.append('status', '2')
                                    await dispatch(
                                      changeStatusOfLicense({id: item.id, status: 2})
                                    ).unwrap()
                                   

                                    setDataa(!dataa)
                                    Swal.fire({
                                      title: t('printedCard.successForConfirm'),
                                      icon: 'success',
                                      timer: 3000,
                                    })
                                  } catch (error) {
                                    Swal.fire({
                                      title: t('global.error'),
                                      text: t('printedCard.ConfirmNotSent'),
                                      icon: 'error',
                                    })
                                  }
                                } else {
                                  swalWithBootstrapButtons.fire({
                                    title: t('printedCard.confirmNot'),
                                    text: t('printedCard.confirmNotSub'),
                                    icon: 'error',
                                  })
                                }
                              }}
                            >
                              <i className='fas fa-check text-success me-2'></i>
                              تایید
                            </Dropdown.Item>
                          )}
                          {item.status === 1 && hasPermission('cards-print-reject') && (
                            <Dropdown.Item
                              onClick={() => {
                                setRejectTargetId(item.id)
                                setRejectReason('')
                                setShowRejectModal(true)
                              }}
                            >
                              <i className='fas fa-x text-danger me-2'></i>رد کردن
                            </Dropdown.Item>
                          )}
                          {hasPermission('cards-view-income') && (
                            <Dropdown.Item
                              onClick={() => {
                                setSelectedId(item.id)
                                setShowViewModal(true)
                              }}
                            >
                              <i className='fas fa-eye text-primary me-2'></i>
                              {t('global.view', {name: t('license.license')})}
                            </Dropdown.Item>
                          )}
                          {item.status === 2 && hasPermission('cards-view-income') && (
                            <Dropdown.Item onClick={() => handleServerPrint(item.id)}>
                              <i className='fas fa-print text-info me-2'></i>
                              {t('global.print')}
                            </Dropdown.Item>
                          )}
                        </DropdownButton>
                      </td>
                    </tr>
                  ))}
              </tbody>
            </table>
          </div>

          {/* Reject Modal */}
          {showRejectModal && (
            <div className='modal fade show d-block' style={{backgroundColor: 'rgba(0,0,0,0.5)'}}>
              <div className='modal-dialog'>
                <div className='modal-content'>
                  <div className='modal-header'>
                    <h5 className='modal-title'>رد کردن درخواست</h5>
                    <button
                      type='button'
                      className='btn-close'
                      onClick={() => setShowRejectModal(false)}
                    />
                  </div>
                  <div className='modal-body'>
                    <label>علت رد کردن:</label>
                    <input
                      type='text'
                      className='form-control'
                      value={rejectReason}
                      onChange={(e) => setRejectReason(e.target.value)}
                      placeholder='لطفا علت رد را وارد کنید'
                    />
                  </div>
                  <div className='modal-footer'>
                    <button className='btn btn-secondary' onClick={() => setShowRejectModal(false)}>
                      لغو
                    </button>
                    <button
                      className='btn btn-danger'
                      onClick={async () => {
                        if (!rejectReason.trim()) {
                          Swal.fire({title: 'خطا', text: 'علت رد را وارد کنید!', icon: 'error'})
                          return
                        }
                        if (rejectTargetId !== null) {
                          try {
                            await dispatch(
                              changeStatusOfLicense({
                                id: rejectTargetId,
                                status: 4, // use 4 for "rejected"
                                reason: rejectReason,
                              })
                            ).unwrap()
                            Swal.fire({
                              title: 'رد شد',
                              text: 'درخواست با موفقیت رد شد',
                              icon: 'success',
                              timer: 3000,
                            })
                            setDataa((prev) => !prev)
                          } catch {
                            Swal.fire({
                              title: 'خطا',
                              text: 'رد کردن با خطا مواجه شد',
                              icon: 'error',
                            })
                          } finally {
                            setShowRejectModal(false)
                            setRejectReason('')
                            setRejectTargetId(null)
                          }
                        }
                      }}
                    >
                      ارسال
                    </button>
                  </div>
                </div>
              </div>
            </div>
          )}

          {showViewModal && selectedId && (
            <ViewPrintedCard id={selectedId} onClose={() => setShowViewModal(false)} />
          )}

          {pagination.total && (
            <Paginator
              pagination={pagination}
              pageChanged={setCurrentPage}
              totalItems={pagination.total}
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
