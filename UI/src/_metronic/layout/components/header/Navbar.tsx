// import clsx from 'clsx'
// import {KTSVG} from '../../../helpers'
// import {useLayout} from '../../core'
// import HeaderUserMenu from '../../../partials/layout/header-menus/HeaderUserMenu'
// import Language from '../../../partials/layout/theme-mode/Language'
// import {useAppSelector} from '../../../../redux/hooks'
// import {useAuth} from '../../../../app/modules/auth'
// const itemClass = 'ms-1 ms-lg-3'
// const userAvatarClass = 'symbol-35px symbol-md-40px'
// const btnIconClass = 'svg-icon-1'
// const Navbar = () => {
//   const {config} = useLayout()
//   const {currentUser} = useAuth()
//     console.log(currentUser);
//   return (
//     <div className='app-navbar flex-shrink-0'>
       
//       <div className='app-navbar-item'>
//         <select>
//           <option value={0}>Just Checking</option>
//         </select>
//       </div>
     
//       <div className={clsx('app-navbar-item', itemClass)}>
//         <Language toggleBtnClass={clsx('btn-sm btn-custom')} />
//       </div>
   
//       <div className={clsx('app-navbar-item', itemClass)}>
//         <div
//           className={clsx('cursor-pointer symbol', userAvatarClass)}
//           data-kt-menu-trigger="{default: 'click'}"
//           data-kt-menu-attach='parent'
//           // data-kt-menu-placement='bottom-end'
//           data-kt-menu-placement='bottom-start'
//         >
//           <p className='text-white mt-4 fw-bold fs-4'>{currentUser?.name}</p>
//         </div>
//         <HeaderUserMenu />
//       </div>
 
//       {config.app?.header?.default?.menu?.display && (
//         <div className='app-navbar-item d-lg-none ms-2 me-n3' title='Show header menu'>
//           <div
//             className='btn btn-icon btn-active-color-primary w-35px h-35px'
//             id='kt_app_header_menu_toggle'
//           >
//             <KTSVG path='/media/icons/duotune/text/txt001.svg' className={btnIconClass} />
//           </div>
//         </div>
//       )}
//     </div>
//   )
// }
// export default Navbar
import clsx from 'clsx'
import { KTSVG } from '../../../helpers'
import { useLayout } from '../../core'
import HeaderUserMenu from '../../../partials/layout/header-menus/HeaderUserMenu'
import Language from '../../../partials/layout/theme-mode/Language'
import { useAuth } from '../../../../app/modules/auth'
import axios from 'axios'
const itemClass = 'ms-1 ms-lg-3'
const userAvatarClass = 'symbol-35px symbol-md-40px'
const btnIconClass = 'svg-icon-1'

const Navbar = () => {
  const { config } = useLayout()
  const { currentUser } = useAuth()

 

    const handleProvinceSelect =async (e: React.ChangeEvent<HTMLSelectElement>)=>
  {
      const value = e.target.value;
      //   const response = await axios.get(`api/JumpToOtherProject/${value}`,
      // {
      //    withCredentials: true
      // }
    // );
    window.location.href = `https://localhost:7161/Project/JumpBetweenTheProject?projectid=${value}`;

 
     
  }
  const handleRedirect =()=>
  {
       window.location.href = "https://localhost:7161/home/Index";
  }
  return (
    <div className='app-navbar flex-shrink-0'>
   {/* LANGUAGE */}
      <div className={clsx('app-navbar-item', itemClass)}>
        <Language toggleBtnClass={clsx('btn-sm btn-custom')} />
      </div>
          <div className='app-navbar-item ms-3 ms-lg-4'>
        <button className='btn btn-dark' onClick={handleRedirect}>To Main Page</button>
      </div>
      {/* PROJECT DROPDOWN */}
       <div className='app-navbar-item ms-3 ms-lg-4'>
        <select onChange={handleProvinceSelect}
          className='form-select form-select-sm form-select-solid'
          style={{ minWidth: '200px' }}
          defaultValue=''
        >
          <option value='' disabled>
            Select Project
          </option>

          {currentUser?.projects?.map((project: any, index: number) => (
            <option
              key={index}
              value={project.Id}
            >
              {project.Name}
            </option>
          ))}
        </select>
      </div>

   
 
      {/* USER */}
      <div className={clsx('app-navbar-item', itemClass)}>
        <div
          className={clsx('cursor-pointer symbol', userAvatarClass)}
          data-kt-menu-trigger="{default: 'click'}"
          data-kt-menu-attach='parent'
          data-kt-menu-placement='bottom-start'
        >
          <p className='text-white mt-4 fw-bold fs-4'>
            {currentUser?.name}
          </p>
        </div>

        <HeaderUserMenu />
      </div>

      {/* MOBILE MENU */}
      {config.app?.header?.default?.menu?.display && (
        <div
          className='app-navbar-item d-lg-none ms-2 me-n3'
          title='Show header menu'
        >
          <div
            className='btn btn-icon btn-active-color-primary w-35px h-35px'
            id='kt_app_header_menu_toggle'
          >
            <KTSVG
              path='/media/icons/duotune/text/txt001.svg'
              className={btnIconClass}
            />
          </div>
        </div>
      )}

    </div>
  )
}

export default Navbar