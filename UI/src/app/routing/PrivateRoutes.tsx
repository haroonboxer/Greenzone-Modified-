import {lazy, FC, Suspense} from 'react'
import {Route, Routes, Navigate} from 'react-router-dom'
import MasterLayout from '../../_metronic/layout/MasterLayout'
import TopBarProgress from 'react-topbar-progress-indicator'
import DashboardWrapper from '../pages/dashboard/DashboardWrapper'
import {getCSSVariableValue} from '../../_metronic/assets/ts/_utils'
import {WithChildren} from '../../_metronic/helpers'
import GZRoutes from 'app/modules/green_zone/GZRoutes'
import CardPrintRoutes from 'app/modules/green_zone/card-print/CardPrintRoutes'
import ReportRoutes from 'app/modules/green_zone/Reports/ReportRoutes'

const PrivateRoutes = () => {
  const AccountPage = lazy(() => import('../modules/accounts/AccountPage'))
  const AuthPage = lazy(() => import('../modules/authentication/AuthPage'))

  return (
    <Routes>
      <Route element={<MasterLayout />}>
        <Route path='auth/*' element={<Navigate to='/dashboard' />} />
        {/* Pages */}
        <Route path='dashboard' element={<DashboardWrapper />} />
        {/* Lazy Modules */}
        <Route
          path='authentication/*'
          element={
            <SuspensedView>
              <AuthPage />
            </SuspensedView>
          }
        />
        <Route
          path='report/*'
          element={
            <SuspensedView>
              <ReportRoutes />
            </SuspensedView>
          }
        />
        <Route
          path='card-print/*'
          element={
            <SuspensedView>
              <CardPrintRoutes />
            </SuspensedView>
          }
        />
        <Route
          path='green-zone/*'
          element={
            <SuspensedView>
              <GZRoutes />
            </SuspensedView>
          }
        />

        <Route
          path='crafted/account/*'
          element={
            <SuspensedView>
              <AccountPage />
            </SuspensedView>
          }
        />

        {/* Page Not Found */}
        <Route path='*' element={<Navigate to='/error/404' />} />
      </Route>
    </Routes>
  )
}

const SuspensedView: FC<WithChildren> = ({children}) => {
  const baseColor = getCSSVariableValue('--kt-primary')
  TopBarProgress.config({
    barColors: {
      '0': baseColor,
    },
    barThickness: 1,
    shadowBlur: 5,
  })
  return <Suspense fallback={<TopBarProgress />}>{children}</Suspense>
}

export {PrivateRoutes}
