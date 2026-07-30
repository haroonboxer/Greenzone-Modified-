import {useEffect} from 'react'
import {Outlet} from 'react-router-dom'
import {toAbsoluteUrl} from '../../../_metronic/helpers'

const AuthLayout = () => {
  useEffect(() => {
    const root = document.getElementById('root')
    if (root) {
      root.style.height = '100%'
    }
    return () => {
      if (root) {
        root.style.height = 'auto'
      }
    }
  }, [])

  return (
    <div className='d-flex flex-column flex-lg-row flex-column-fluid h-100'>
      {/* begin::Aside */}
      <div
        className='d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2'
        style={{backgroundImage: `url(${toAbsoluteUrl('/media/images/moi2.jpg')})`}}
      >
        <div className='d-flex flex-column flex-lg-row-fluid w-lg-60 p-10 order-2 order-lg-1'>
          {/* begin::Form */}
          <div className='d-flex flex-center flex-column flex-lg-row-fluid'>
            {/* begin::Wrapper */}
            <div
              className='w-lg-500px p-10 shadow-lg rounded'
              style={{
                backgroundColor: 'rgba(255, 255, 255, 0.3)', // Increased opacity of the card's background (adjust alpha value to suit your needs)
                backdropFilter: 'blur(8px)', // Optional: Adds blur effect to the background behind the card
              }}
            >
              <Outlet />
            </div>
            {/* end::Wrapper */}
          </div>
          {/* end::Form */}
        </div>
      </div>
      {/* end::Aside */}
    </div>
  )
}

export {AuthLayout}
