import {Fragment, useState} from 'react'
import {Link} from 'react-router-dom'
import {Dropdown, DropdownButton} from 'react-bootstrap'
import DepartmentTree from './DepartmentTree'
import {useAuth} from '../../../../auth/core/Auth'
import {useTranslation} from 'react-i18next'

const DepartmentList = () => {
  const {hasPermission} = useAuth()
  const {t} = useTranslation()
  const [nodeId, setNodeId] = useState<any>()
  type CheckboxChangeEvent = React.ChangeEvent<HTMLInputElement>
  const handleToggleCheckbox = (event: CheckboxChangeEvent, node_id: number) => {
    if (event.target.checked) {
      setNodeId(node_id)
    } else {
      setNodeId(undefined)
    }
  }
  return (
    <>
      <Fragment>
        <div className='card mb-5 mb-xl-10' id='kt_profile_details_view'>
          <div className='card-header cursor-pointer'>
            <div className='card-title m-0'>
              <h3 className='fw-bolder m-0'>{t('global.list', {name: t('user.department')})}</h3>
            </div>
            <div>
              <div className='d-none d-lg-flex mt-5'>
                <div className='d-flex align-items-center'>
                  {hasPermission('admin-create') && (
                    <Link
                      className={`btn btn-sm btn-flex btn-primary fw-bolder me-2 ${
                        nodeId === undefined ? 'disabled' : ''
                      }`}
                      to={'/authentication/create-department/' + nodeId}
                    >
                      <i className='fa-solid fa-plus'></i>

                      {t('global.add', {name: t('user.department')})}
                    </Link>
                  )}

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
                    {hasPermission('admin-create') && (
                      <Dropdown.Item as='button'>
                        <Link
                          className='fw-bolder text-primary'
                          to={'/authentication/create-department/' + nodeId}
                        >
                          <i className='fa-solid fa-plus  text-primary me-2'></i>

                          {t('GLOBAL.ADD')}
                        </Link>
                      </Dropdown.Item>
                    )}

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
            <DepartmentTree handleToggleCheckbox={handleToggleCheckbox} />
          </div>
        </div>
      </Fragment>
    </>
  )
}
//@ts-ignore
export default DepartmentList
