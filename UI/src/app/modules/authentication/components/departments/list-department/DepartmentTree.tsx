import {useEffect, useState, useMemo} from 'react'
import UnAuthorized from '../../../../../customes/UnAuthorized'
import Loader from '../../../../../pages/loading/Loader'
import {useAppDispatch, useAppSelector} from '../../../../../../redux/hooks'
import {getDepartments} from '../../../../../../redux/authentication/department/departmentSlice'
import {ReactTree} from '@naisutech/react-tree'

const DepartmentTree = ({handleToggleCheckbox}: any) => {
  const [data, setData] = useState([])
  const [isAuthorized, setIsAuthorized] = useState<boolean>(true)
  const [loading, setLoading] = useState<boolean>(true)

  const dispatch = useAppDispatch()
  const {departments} = useAppSelector((state: any) => state.departments)
  useEffect(() => {
    dispatch(getDepartments()).then((res) => {
      if (res.meta.requestStatus === 'fulfilled') {
        setLoading(true)
      } else if (res.meta.requestStatus === 'rejected') {
        setIsAuthorized(false)
      }
      setLoading(false)
    })
  }, [dispatch])

  useEffect(() => {
    setData(departments)
  }, [departments])
  type CheckboxChangeEvent = React.ChangeEvent<HTMLInputElement>

  const memoizedData = useMemo(() => data, [data])
  const memoizedLoading = useMemo(() => loading, [loading])

  return (
    <div>
      {isAuthorized ? (
        <>
          <div className='tableFixHead  table-responsive' dir='rtl'>
            {!memoizedLoading && (
              <ReactTree
                containerStyles={{}}
                enableIndicatorAnimations
                enableItemAnimations
                messages={{
                  loading: 'Loading...',
                  noData: 'No data to render 😔',
                }}
                nodes={memoizedData}
                onToggleOpenNodes={function noRefCheck() {}}
                // onToggleSelectedNodes={handleToggleSelectedNodes}
                RenderNode={(node: any) => (
                  <>
                    <label className='form-check form-check-inline'>
                      <input
                        type='checkbox'
                        checked={node.checked}
                        // onChange={() => handleToggleCheckbox(node.node.id)}
                        onChange={(event: CheckboxChangeEvent) =>
                          handleToggleCheckbox(event, node.node.id)
                        }
                        className='ms-1 me-1 mt-2 form-check-input'
                      />
                      <span className='fs-4 fw-bold'>{node.node.label}</span>
                    </label>
                  </>
                )}
                theme='light'
                themes={{
                  exampleCustomTheme: {
                    nodes: {
                      folder: {
                        bgColor: 'gold',
                        hoverBgColor: 'yellow',
                        selectedBgColor: 'goldenrod',
                      },
                      height: '3.5rem',
                      icons: {
                        folderColor: 'crimson',
                        leafColor: 'white',
                        size: '1rem',
                      },
                      leaf: {
                        bgColor: 'magenta',
                        hoverBgColor: 'violet',
                        selectedBgColor: 'blueviolet',
                      },
                      separator: {
                        border: '3px solid',
                        borderColor: 'transparent',
                      },
                    },
                    text: {
                      color: '#fafafa',
                      fontFamily: 'cursive',
                      fontSize: 'xl',
                      hoverColor: '#fafafa',
                      selectedColor: '#fafafa',
                    },
                  },
                }}
              />
            )}
            {memoizedLoading && <Loader />}
          </div>
        </>
      ) : (
        <UnAuthorized />
      )}
    </div>
  )
}

export default DepartmentTree
