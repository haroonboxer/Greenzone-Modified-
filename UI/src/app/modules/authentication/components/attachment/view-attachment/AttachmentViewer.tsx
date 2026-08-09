import {useEffect, useState} from 'react'
import axios from 'axios'
import Loader from 'app/pages/loading/Loader'
import {useTranslation} from 'react-i18next'
import AttachmentPaginator from '../../../../../customes/AttachmentPaginator'
import {downloadFile} from 'helpers/Functions'

const AttachmentViewer = ({id, form_code}: any) => {
  const [loading, setLoading] = useState(true)
  const [data, setData] = useState([])
  const [pagination, setPagination] = useState({})
  const [currentPage, setCurrentPage] = useState(1)
  const [perPage] = useState(1)
  const {t} = useTranslation()
  const fetchData = async () => {
    setLoading(true)
    const params = {
      per_page: 1,
      page: currentPage,
      id: id,
      form_code: form_code,
    }
    try {
      const {data} = await axios(`/api/attachment/index`, {params})
      setData(data.data)
      setPagination(data.meta)
    } catch (error) {
   
    }
    setTimeout(() => {
      setLoading(false)
    }, 100)
  }

  useEffect(() => {
    fetchData()
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [perPage, currentPage])

  return (
    <>
      {data.length === 0 && !loading ? (
        <p className='text-danger text-center message-color'>
          {t('global.noAttachmentRecordFound')}
        </p>
      ) : (
        ''
      )}
      {!loading ? (
        data.map((row: any, index: any) => {
          return (
            <div className='text-center' key={index}>
              <img
                style={{maxWidth: '100%', maxHeight: '100%', transition: 'transform 0.3s'}}
                src={process.env.REACT_APP_API_URL + '/storage/' + row.path_name}
                alt='file'
              />
              <div className='d-flex justify-content-center my-2'>
                <button
                  type='button'
                  className='btn btn-primary bt-sm me-2 fa-solid fa-download'
                  onClick={() => downloadFile('api/attachment/download', row.id, row.file_name)}
                ></button>
              </div>
            </div>
          )
        })
      ) : (
        <Loader />
      )}

      {data.length > 0 && !loading ? (
        <div className='mt-2'>
          <AttachmentPaginator
            pagination={pagination}
            pageChanged={(page: number) => setCurrentPage(page)}
            totalItems={data.length}
          />
        </div>
      ) : null}
    </>
  )
}

export default AttachmentViewer
