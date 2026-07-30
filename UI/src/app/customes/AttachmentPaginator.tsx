import {useEffect, useState} from 'react'
const OFFSET = 4
const AttachmentPaginator = ({pagination, pageChanged}: any) => {
  const [pageNumbers, setPageNumbers] = useState([])
  useEffect(() => {
    ;(async () => {
      let pages: any = []
      const {last_page, current_page, to} = pagination
      if (!to) return []
      let fromPage = current_page - OFFSET
      if (fromPage < 1) fromPage = 1
      let toPage = fromPage + OFFSET * 2
      if (toPage >= last_page) {
        toPage = last_page
      }
      for (let page = fromPage; page <= toPage; page++) {
        pages.push(page)
      }
      setPageNumbers(pages)
    })()
  }, [pagination])

  return (
    <div className='flex-stack flex-wrap pt-10'>
      <ul className='pagination'>
        <li className={'page-item' + (pagination.current_page === 1 ? ' disabled' : '')}>
          <button className='page-link' onClick={() => pageChanged(pagination.current_page - 1)}>
            <i className='fas fa-angle-right fs-4'></i>
          </button>
        </li>
        {pageNumbers.map((pageNumber) => {
          return (
            <li
              className={'page-item' + (pageNumber === pagination.current_page ? ' active' : '')}
              key={pageNumber}
              onClick={() => pageChanged(pageNumber)}
            >
              <button className='page-link '>{pageNumber}</button>
            </li>
          )
        })}

        <li
          className={
            'page-item' + (pagination.current_page === pagination.last_page ? ' disabled' : '')
          }
        >
          <button className='page-link' onClick={() => pageChanged(pagination.current_page + 1)}>
            <i className='fas fa-angle-left fs-4 fw-bold'></i>
          </button>
        </li>
      </ul>
      {/* <div className='fs-6 fw-bold text-gray-700 d-none d-lg-flex'>
        <span className='pagination-total pagination__desc'>
          {t('global.PAGINATIONTOTALRECORD')}
          <span>
            <b>{pagination?.total}</b>
          </span>
          &nbsp; {t('global.PAGINATIONFROM')}
          <span>
            <b>
              {pagination.from} الا - {pagination.to}
            </b>
            &nbsp;&nbsp;
          </span>
          {t('global.PAGINATIONPAGE')}
          <span>
            <b>{pagination.current_page}</b>
          </span>
        </span>
      </div> */}
    </div>
  )
}

export default AttachmentPaginator
