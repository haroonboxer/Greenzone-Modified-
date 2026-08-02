import { FC, memo } from 'react'
import { PageTitle } from '../../../_metronic/layout/core'
import ModulesItem from '../../modules/reusable-components/ModulesItem'
import { useTranslation } from 'react-i18next'
// import CompanyMonthlyChart from 'app/modules/rms/Reports/CompanyMonthlyChart'

const DashboardWrapper: FC = () => {

  const { t } = useTranslation()

  
  return (
      
    <>
      <PageTitle breadcrumbs={[]}>{t('global.dashboard')}</PageTitle>
      <div className='row gy-5 g-xl-8'>
      
          <ModulesItem
            key={1}
            title={'سیستم مدیریتی جواز وسایط برای ساحات سبز'}
            text={"سیستم مدیریتی جواز وسایط برای ساحات سبز"}
            link={'/green-zone/list'}
            icon={'group.png'}
          />
       
      </div>
      {/* <CompanyMonthlyChart /> */}
    </>
  )
}

export default DashboardWrapper
