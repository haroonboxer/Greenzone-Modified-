import { FC, memo } from 'react'
import { PageTitle } from '../../../_metronic/layout/core'
import ModulesItem from '../../modules/reusable-components/ModulesItem'
import { useAuth } from '../../modules/auth'
import { useTranslation } from 'react-i18next'
// import CompanyMonthlyChart from 'app/modules/rms/Reports/CompanyMonthlyChart'

const DashboardWrapper: FC = () => {
  const { currentUser } = useAuth()
  const { t } = useTranslation()
  console.log('User systems:', currentUser?.systems)
  return (
    <>
      <PageTitle breadcrumbs={[]}>{t('global.dashboard')}</PageTitle>
      <div className='row gy-5 g-xl-8'>
        {currentUser?.systems.map((system: any, i: number) => (
          <ModulesItem
            key={i}
            title={system.name_da}
            text={system.name_da}
            link={system.route}
            icon={system.icon}
          />
        ))}
      </div>
      {/* <CompanyMonthlyChart /> */}
    </>
  )
}

export default DashboardWrapper
