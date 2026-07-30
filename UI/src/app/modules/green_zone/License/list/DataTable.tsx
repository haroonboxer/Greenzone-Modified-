import {debounce} from 'lodash'
import Paginator from 'app/customes/Paginator'
import UnAuthorized from 'app/customes/UnAuthorized'
import Loader from 'app/pages/loading/Loader'
import {useEffect, useMemo, useRef, useState} from 'react'
import {Dropdown, DropdownButton} from 'react-bootstrap'
import {useTranslation} from 'react-i18next'
import {useAppDispatch, useAppSelector} from 'redux/hooks'
import {useParams} from 'react-router-dom'
import {changeStatusOfPrint, getLicense, sentPrint} from 'redux/green_zone/license/licenseSlice'
import {useAuth} from 'app/modules/auth'
import LicenseViewModal from '../view/LicenseViewModal'
import LicenseEditModal from '../edit/LicenseEditModal'
import Swal from 'sweetalert2'
import OverlayTrigger from 'react-bootstrap/OverlayTrigger'
import Tooltip from 'react-bootstrap/Tooltip'
import axios from 'axios'

const SORT_ASC = 'asc'
const SORT_DESC = 'desc'

const DataTable: React.FC<any> = ({headers, columns, onRecordsChange}) => {
  const [data, setData] = useState<any[]>([])
  const [perPage, setPerPage] = useState<number>(10)
  const [sortColumn, setSortColumn] = useState<string>(columns[0])
  const [sortOrder, setSortOrder] = useState<string>(SORT_DESC)
  const [isAuthorized, setIsAuthorized] = useState<boolean>(true)
  const [pagination, setPagination] = useState<any>({})
  const [currentPage, setCurrentPage] = useState<number>(1)
  const [loading, setLoading] = useState<boolean>(true)
  const [showViewModal, setShowViewModal] = useState<boolean>(false)
  const [dataa, setDataa] = useState<boolean>(false)
  const [showEditModal, setShowEditModal] = useState<boolean>(false)
  const [selectedId, setSelectedId] = useState<number | null>(null)
  const [selectedLicense, setSelectedLicense] = useState<any | null>(null)
  const [showRejectReasonModal, setShowRejectReasonModal] = useState(false)
  const [rejectReason, setRejectReason] = useState<string>('')

  const [searchTerm, setSearchTerm] = useState('') // SN
  const [searchDriver, setSearchDriver] = useState('') // Driver Name
  const [licenseType, setLicenseType] = useState('') // License Type

  const {id} = useParams<{id: string}>()
  const {licenseIndex} = useAppSelector((state) => state.gzLicense)
  const dispatch = useAppDispatch()
  const {t} = useTranslation()
  const {hasPermission} = useAuth()

  const params = useMemo(
    () => ({
      sort_field: sortColumn,
      sort_order: sortOrder,
      per_page: perPage,
      page: currentPage,
      vehicle_id: id,
      sn: searchTerm,
      driver_name: searchDriver,
      license_type: licenseType,
    }),
    [sortColumn, sortOrder, perPage, currentPage, id, searchTerm, searchDriver, licenseType]
  )

  // Sorting
  const handleSort = (column: string) => {
    setLoading(true)
    setSortOrder((prevSortOrder) =>
      column === sortColumn ? (prevSortOrder === SORT_ASC ? SORT_DESC : SORT_ASC) : SORT_ASC
    )
    setSortColumn(column)
  }

  const handlePerPage = (newPerPage: number) => {
    setPerPage(newPerPage)
    setCurrentPage(1)
  }

  // Debounced Search Handlers
  const handleSearchLicense = useRef(
    debounce((query) => {
      setLoading(true)
      setSearchTerm(query)
      setCurrentPage(1)
      setSortOrder(SORT_ASC)
      setSortColumn(columns[0])
    }, 500)
  ).current

  const handleSearchDriver = useRef(
    debounce((query) => {
      setLoading(true)
      setSearchDriver(query)
      setCurrentPage(1)
    }, 500)
  ).current

  const handleLicenseTypeChange = (type: string) => {
    setLicenseType(type)
    setCurrentPage(1)
  }

  // Fetch licenses
  useEffect(() => {
    setLoading(true)
    dispatch(getLicense(params)).then((res) => {
      if (res.meta.requestStatus === 'fulfilled') {
        setLoading(false)
      } else {
        setIsAuthorized(false)
        setLoading(false)
      }
    })
  }, [params, dispatch])

  useEffect(() => {
    setData(licenseIndex.data || [])
    setPagination(licenseIndex.meta || {})
  }, [licenseIndex])

  const memoizedData = useMemo(() => data, [data])
  const memoizedLoading = useMemo(() => loading, [loading])

  // Reject Reason Modal
  const RejectReasonModal: React.FC<{
    show: boolean
    onClose: () => void
    reason: string
  }> = ({show, onClose, reason}) => {
    if (!show) return null
    return (
      <div
        className='modal fade show'
        tabIndex={-1}
        role='dialog'
        style={{display: 'block', backgroundColor: 'rgba(0,0,0,0.5)'}}
      >
        <div
          className='modal-dialog modal-dialog-centered'
          role='document'
          style={{maxWidth: '500px'}}
        >
          <div className='modal-content'>
            <div className='modal-header'>
              <h5 className='modal-title'>{t('global.rejectReason')}</h5>
              <button type='button' className='btn-close' aria-label='Close' onClick={onClose} />
            </div>
            <div className='modal-body'>
              <p>{reason || t('global.noReasonProvided')}</p>
            </div>
            <div className='modal-footer'>
              <button className='btn btn-danger' onClick={onClose}>
                {t('global.close')}
              </button>
            </div>
          </div>
        </div>
      </div>
    )
  }
  // ====================================================================================================================================
  // const handleAfterPrint = async (wid: number) => {
  //   const result = await Swal.fire({
  //     title: 'تایید چاپ کارت',
  //     text: 'آیا کارت درست چاپ شده است؟',
  //     icon: 'question',
  //     confirmButtonText: 'بلی',
  //     cancelButtonText: 'نخیر',
  //     showCancelButton: true,
  //     allowOutsideClick: false,
  //   })
  //   const status = result.isConfirmed ? 1 : 0
  //   try {
  //     await dispatch(changeStatusOfPrint({id: wid, status})).unwrap()
  //     Swal.fire({
  //       title: result.isConfirmed ? 'تایید شد' : 'رد شد',
  //       icon: result.isConfirmed ? 'success' : 'info',
  //       timer: 3000,
  //     })
  //     setDataa((prev) => !prev)
  //   } catch {
  //     Swal.fire({title: 'خطا', text: 'ثبت وضعیت با خطا مواجه شد', icon: 'error'})
  //   }
  // }
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
          {/* Filters */}
          <div className='form collapse' id='movementSearch'>
            <div className='row mb-8 col-md-12'>
              {/* Search SN */}
              <div className='col-md-3'>
                <input
                  type='search'
                  placeholder={t('license.searchBySlipNo')}
                  className='form-control form-control-sm search-input'
                  onChange={(e) => handleSearchLicense(e.target.value)}
                />
              </div>
              {/* Search Driver */}
              <div className='col-md-3'>
                <input
                  type='search'
                  placeholder={t('license.searchByDriver')}
                  className='form-control form-control-sm search-input'
                  onChange={(e) => handleSearchDriver(e.target.value)}
                />
              </div>
              {/* License Type Dropdown */}
              <div className='col-md-3'>
                <select
                  className='form-select'
                  value={licenseType}
                  onChange={(e) => handleLicenseTypeChange(e.target.value)}
                >
                  <option value=''>{t('license.allTypes')}</option>
                  <option value='new'>{t('license.new')}</option>
                  <option value='renew'>{t('license.renew')}</option>
                  <option value='extend'>{t('license.extend')}</option>
                </select>
              </div>
              {/* Records Per Page */}
              <div className='col-md-3' style={{marginTop: '-20px'}}>
                <label>{t('global.recordsPerPage')}</label>
                <select
                  className='form-select'
                  value={perPage}
                  onChange={(e) => handlePerPage(Number(e.target.value))}
                >
                  {[5, 10, 20, 50].map((value) => (
                    <option key={value} value={value}>
                      {value}
                    </option>
                  ))}
                </select>
              </div>
            </div>
          </div>

          {/* Table */}
          <div
            className='table-responsive tableFixHead'
            dir='rtl'
            style={{overflowX: 'auto', whiteSpace: 'nowrap'}}
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
                      <td className='fw-bolder text-center'>{memoizedData.length - index}</td>
                      <td className='text-center'>
                        {item.license_type === 'new' ? (
                          <span
                            className='badge bg-success bg-opacity-25 text-dark'
                            style={{fontSize: '1rem'}}
                          >
                            {t('license.new')}
                          </span>
                        ) : item.license_type === 'renew' ? (
                          <span
                            className='badge bg-primary bg-opacity-25 text-dark'
                            style={{fontSize: '1rem'}}
                          >
                            {t('license.renew')}
                          </span>
                        ) : (
                          <span
                            className='badge bg-warning bg-opacity-25 text-dark'
                            style={{fontSize: '1rem'}}
                          >
                            {t('license.extend')}
                          </span>
                        )}
                      </td>
                      <td className='text-center'>{item.sn}</td>
                      <td className='text-center'>{item.driver_name}</td>
                      <td className='text-center'>
                        <span className='badge bg-primary me-1' style={{fontSize: '1rem'}}>
                          {t('license.issue_date')}:{' '}
                          {`${item.issue_date.split('-')[2]}-${item.issue_date.split('-')[1]}-${
                            item.issue_date.split('-')[0]
                          }`}
                        </span>
                        <span className='mx-1' style={{fontSize: '1.2rem'}}>
                          الی
                        </span>
                        <span className='badge bg-primary ms-1' style={{fontSize: '1rem'}}>
                          {t('gzlicense.expire_date')}:{' '}
                          {`${item.expire_date.split('-')[2]}-${item.expire_date.split('-')[1]}-${
                            item.expire_date.split('-')[0]
                          }`}
                        </span>
                      </td>
                      <td className='text-center'>{item.ownerName}</td>
                      <td className='text-center'>
                        {item.printed === 1 ? (
                          <span className='badge badge-secondary'>{t('global.printed')}</span>
                        ) : item.status === 0 ? (
                          <span className='badge badge-warning'>{t('global.notSent')}</span>
                        ) : item.status === 1 ? (
                          <>
                            <span className='badge badge-info'>{t('global.sentToPrint')}</span>
                            &nbsp;&nbsp;
                            <span className='badge badge-info'>{t('global.waiting')}</span>
                          </>
                        ) : item.status === 2 ? (
                          <span className='badge badge-success'>{t('global.accept')}</span>
                        ) : item.status === 4 ? (
                          <OverlayTrigger
                            placement='top'
                            overlay={
                              <Tooltip id={`tooltip-rejected-${item.id}`}>
                                {t('global.clickToViewReason')}
                              </Tooltip>
                            }
                          >
                            <>
                              <span
                                className='badge badge-danger'
                                style={{cursor: 'pointer'}}
                                onClick={() => {
                                  setRejectReason(item.reject_reason)
                                  setShowRejectReasonModal(true)
                                }}
                              >
                                <i className='text-white fas fa-info-circle ms-1' />
                                &nbsp;&nbsp;{t('global.rejected')}
                              </span>
                              &nbsp;&nbsp;
                              <span
                                className='badge badge-danger'
                                style={{cursor: 'pointer'}}
                                onClick={() => {
                                  setRejectReason(item.reject_reason)
                                  setShowRejectReasonModal(true)
                                }}
                              >
                                <i className='text-white fas fa-info-circle ms-1' /> &nbsp;&nbsp;
                                {t('global.recheck')}
                              </span>
                            </>
                          </OverlayTrigger>
                        ) : null}
                      </td>
                      <td className='text-center'>
                        <DropdownButton id='dropdown-item-button' size='sm' title='⋮'>
                          {hasPermission('license-view') && (
                            <Dropdown.Item
                              as='button'
                              onClick={() => {
                                setSelectedId(item.id)
                                setShowViewModal(true)
                              }}
                            >
                              <i className='fas fa-eye text-primary me-2'></i>
                              {t('global.view', {name: t('license.license')})}
                            </Dropdown.Item>
                          )}
                          {(item.status === 4 || item.status === 0) &&
                            hasPermission('license-edit') && (
                              <Dropdown.Item
                                as='button'
                                onClick={() => {
                                  setSelectedLicense(item)
                                  setShowEditModal(true)
                                }}
                              >
                                <i className='fas fa-edit text-warning me-2'></i>
                                {t('global.edit', {name: t('license.license')})}
                              </Dropdown.Item>
                            )}
                          {(item.status === 4 || item.status === 0) &&
                            hasPermission('license-status') && (
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
                                    title: t('printedCard.sendToPrint'),
                                    text: t('printedCard.sendToPrintText'),
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: t('global.yes'),
                                    cancelButtonText: t('global.no'),
                                  })
                                  if (result.isConfirmed) {
                                    try {
                                      const formData = new FormData()
                                      formData.append('id', item.id)
                                      formData.append('status', '1')
                                      await dispatch(sentPrint(formData)).unwrap()
                                      dispatch(getLicense(params))
                                      Swal.fire({
                                        title: t('printedCard.successForSweetAlert'),
                                        icon: 'success',
                                        timer: 3000,
                                      })
                                    } catch (error) {
                                      Swal.fire({
                                        title: t('global.error'),
                                        text: t('printedCard.cardNotSentW'),
                                        icon: 'error',
                                      })
                                    }
                                  } else {
                                    swalWithBootstrapButtons.fire({
                                      title: t('printedCard.cancelled'),
                                      text: t('printedCard.cardNotSentW'),
                                      icon: 'error',
                                    })
                                  }
                                }}
                              >
                                <i className='fas fa-paper-plane text-danger me-2'></i>
                                {t('printedCard.sendToPrint')}
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

          {/* Modals */}
          {showViewModal && selectedId && (
            <LicenseViewModal id={selectedId} onClose={() => setShowViewModal(false)} />
          )}
          {showEditModal && selectedLicense && (
            <LicenseEditModal
              licenseId={selectedLicense}
              showModal={showEditModal}
              onHide={() => setShowEditModal(false)}
              onSuccess={() => {
                setShowEditModal(false)
                dispatch(getLicense(params))
              }}
            />
          )}
          <RejectReasonModal
            show={showRejectReasonModal}
            reason={rejectReason}
            onClose={() => setShowRejectReasonModal(false)}
          />

          {/* Pagination */}
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
