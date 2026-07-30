import React, {useEffect, useState, useRef, useMemo} from 'react'
import {debounce} from 'lodash'
import {Dropdown, DropdownButton} from 'react-bootstrap'
import {useTranslation} from 'react-i18next'
import {useAppDispatch} from 'redux/hooks'
import {useParams} from 'react-router-dom'
import Paginator from 'app/customes/Paginator'
import UnAuthorized from 'app/customes/UnAuthorized'
import Loader from 'app/pages/loading/Loader'
import '../../../../../_metronic/assets/css/dataTable.css'
import {getVehicleSave} from 'redux/green_zone/vehicleSave/vehicleSaveSlice'
import EditModal from './EditModal'

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
  const [searchTerm, setSearchTerm] = useState<string>('')
  const [dataa, setDataa] = useState<boolean>(false)
  const {id} = useParams<{id: string}>()
  const dispatch = useAppDispatch()
  const {t} = useTranslation()
  const [showEditModal, setShowEditModal] = useState(false)
  const [searchInput, setSearchInput] = useState('')
  const [editVehicle, setEditVehicle] = useState<{id: number; name: string} | null>(null)
  // ====================================================================================================================================
  const handleEdit = (vehicle: {id: number; name: string}) => {
    setEditVehicle(vehicle)
    setShowEditModal(true)
  }
  // ====================================================================================================================================
  const params = useMemo(
    () => ({
      sort_field: sortColumn,
      sort_order: sortOrder,
      per_page: perPage,
      page: currentPage,
      vehicle_id: id,
      search: searchTerm,
    }),
    [sortColumn, sortOrder, perPage, currentPage, id, searchTerm]
  )

  useEffect(() => {
    const handler = setTimeout(() => {
      setSearchTerm(searchInput)
      setCurrentPage(1)
      setSortOrder(SORT_ASC)
      setSortColumn(columns[0])
    }, 500)

    return () => clearTimeout(handler) // cleanup previous timeout
  }, [searchInput, columns])

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
    setLoading(true)
    dispatch(getVehicleSave(params)).then((res: any) => {
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
  return (
    <div>
      {isAuthorized ? (
        <>
          <div className='form collapse' id='movementSearch'>
            <div className='row mb-3 mt-3'>
              <div className='col-lg-3 mb-3'>
                <label style={{fontWeight: 'bold'}}>{t('global.NameBased')}</label>
                <input
                  type='search'
                  placeholder={t('global.NameBased')}
                  className='form-control form-control-sm'
                  onChange={(e) => setSearchInput(e.target.value)}
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
                        style={{paddingRight: '1rem', paddingLeft: '1rem', fontSize: '1.3rem'}}
                        className='fw-bolder text-right'
                      >
                        {item.id}
                      </td>
                      <td className='fw-bolder text-right' style={{fontSize: '1.3rem'}}>
                        {item.name}
                      </td>
                      <td className='fw-bolder text-right' style={{fontSize: '1.3rem'}}>
                        {item.ownerName}
                      </td>
                      <td className='text-center'>
                        <DropdownButton
                          id='dropdown'
                          size='sm'
                          title={<i className='fas fa-ellipsis-v text-muted'></i>}
                        >
                          <Dropdown.Item onClick={() => handleEdit(item)}>
                            <i className='fas fa-edit text-warning me-2'></i>تجدید
                          </Dropdown.Item>
                        </DropdownButton>
                      </td>
                    </tr>
                  ))}
              </tbody>
            </table>
          </div>
          <EditModal
            show={showEditModal}
            onHide={() => setShowEditModal(false)}
            onSuccess={() => setDataa(!dataa)}
            vehicle={editVehicle}
          />
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
