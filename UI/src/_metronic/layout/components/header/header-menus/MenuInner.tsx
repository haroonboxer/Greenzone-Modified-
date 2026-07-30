import {MenuItem} from './MenuItem'
import {MenuInnerWithSub} from './MenuInnerWithSub'
import {useTranslation} from 'react-i18next'
import {useAuth} from '../../../../../app/modules/auth'

export function MenuInner() {
  const {t} = useTranslation()
  const {hasPermission} = useAuth()

  const basePath = window.location.pathname.split('/')

  const currentBasePath = basePath[1] || ''

  if (currentBasePath === 'authentication') {
    return (
      <>
        {/* Related to admin */}
        {hasPermission('admin-create') && (
          <MenuInnerWithSub
            title={t('global.SYSTEMANAGEMENT')}
            to='/authentication/directorates'
            menuPlacement='bottom-end'
            menuTrigger='click'
            hasArrow={true}
            fontIcon='fas fa-cogs'
          >
            <MenuItem
              to='/authentication/users'
              title={t('global.users')}
              fontIcon='fas fa-users text-white'
              hasBullet={false}
            />
            <MenuItem
              to='/authentication/departments'
              title={t('user.departments')}
              fontIcon='fas fa-home text-white'
              hasBullet={false}
            />
            <MenuItem
              to='/authentication/roles'
              title={t('user.roles')}
              fontIcon='fas fa-sitemap text-white'
              hasBullet={false}
            />
          </MenuInnerWithSub>
        )}
      </>
    )
  } else if (
    currentBasePath === 'green-zone' ||
    currentBasePath === 'card-print' ||
    currentBasePath === 'report'
  ) {
    return (
      <>
        <MenuItem title={t('global.dashboard')} to='/dashboard' fontIcon='fas fa-home text-white' />
        {hasPermission('vehicle-list') && (
          <MenuInnerWithSub
            title={t('global.listForvehicles')}
            to='#'
            menuPlacement='bottom-end'
            menuTrigger='click'
            hasArrow={true}
            fontIcon='fas fa-car'
          >
            <MenuItem
              to='/green-zone/vehicleSave'
              title={t('global.add', {name: t('vehicle.vehicles')})}
              fontIcon='fas fa-add'
              hasBullet={false}
            />
            <MenuItem
              to='/green-zone/list'
              title={t('global.list', {name: t('vehicle.vehicles')})}
              fontIcon='fas fa-list'
              hasBullet={false}
            />
            <MenuItem
              to='/green-zone/expiredVehicles'
              title={t('global.list', {name: t('vehicle.expiredVehicles')})}
              fontIcon='fas fa-exclamation-triangle'
              hasBullet={false}
            />
          </MenuInnerWithSub>
        )}
        {hasPermission('cards') && (
          <MenuItem
            title={t('global.CardPrint')}
            to='/card-print/list'
            fontIcon='fas fa-id-card text-white'
          />
        )}
        {hasPermission('reports') && (
          <MenuItem
            title={t('global.reports')}
            fontIcon='fas fa-arrow-down-a-z text-white'
            to='/report/list'
          />
        )}
      </>
    )
  } else {
    return null
  }
}
