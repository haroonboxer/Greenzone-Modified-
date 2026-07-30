import { useTranslation } from 'react-i18next'

const RecordOwnerView = (props: any) => {
  const { t } = useTranslation()

  return (
    <div
      className="record-owner-view mb-5"
    >
      <div className="row ">
        {[
          {
            icon: 'fa fa-building',
            label: t('global.departmentName'),
            value: props.departmentName,
          },
          {
            icon: 'fa fa-map-marker-alt',
            label: t('global.recordLocation'),
            value: props.province,
          },
          {
            icon: 'fa fa-user',
            label: t('global.recordOwner'),
            value: props.ownerName,
          },
          {
            icon: 'fa fa-calendar-alt',
            label: t('global.regDate'),
            value: props.created_at
              ? new Date(props.created_at).toLocaleString()
              : null,
          },
        ].map(({ icon, label, value }, idx) => (
          <div key={idx} className="col-lg-3 col-md-6 col-sm-12">
            <div
              className="p-3 border rounded bg-light h-100 d-flex align-items-center"
              style={{ gap: '0.75rem' }}
            >
              <i className={`${icon} text-primary fs-4`} aria-hidden="true" />
              <div>
                <label
                  className="form-label text-muted mb-1 fw-bold"
                  style={{ fontSize: '1.05rem' }}
                >
                  {label}:
                </label>
                <div
                  className="fs-5 text-dark"
                  style={{ wordBreak: 'break-word' }}
                >
                  {value || <em>{t('global.notAvailable')}</em>}
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}

export default RecordOwnerView
