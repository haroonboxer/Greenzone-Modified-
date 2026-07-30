import {Link} from 'react-router-dom'
const DepartmentForm = ({formik, renderErrorMessage, t, loading}: any) => {
  return (
    <div className='card' id='kt_profile_details_view'>
      <div className='card-header cursor-pointer'>
        <div className='card-title m-0'>
          <h3 className='fw-bolder m-0'>ثبت ارگان جدید</h3>
        </div>
      </div>
      <div className='card-body p-0 m-0'>
        <form
          className='form w-100 '
          id='kt_login_signup_form'
          onSubmit={formik.handleSubmit}
          noValidate
          autoComplete='off'
        >
          <div className='card-body'>
            {/* begin::Form row */}
            <div className='row background-fa'>
              <div className='col-lg-4 col-md-4 col-sm-12'>
                <label className='col-form-label label fw-bold fs-5'>
                  {t('directorate.name_en')}:
                  <i className='fa-solid fa-star-of-life text-danger align-text-top fs-9'></i>
                </label>
                <input
                  type='text'
                  name='name_en'
                  placeholder={t('directorate.name_en')}
                  {...formik.getFieldProps('name_en')}
                  className={`form-control form-control-sm ${
                    renderErrorMessage('name_en') ? `validation-error` : ''
                  }`}
                />
                {renderErrorMessage('name_en')}
              </div>

              <div className='col-lg-4 col-md-4 col-sm-12'>
                <label className='col-form-label label fw-bold fs-5'>
                  {t('directorate.name_fa')}:
                  <i className='fa-solid fa-star-of-life text-danger align-text-top fs-9'></i>
                </label>
                <input
                  type='text'
                  name='name_fa'
                  placeholder={t('directorate.name_fa')}
                  {...formik.getFieldProps('name_fa')}
                  className={`form-control form-control-sm ${
                    renderErrorMessage('name_fa') ? `validation-error` : ''
                  }`}
                />
                {renderErrorMessage('name_fa')}
              </div>
              <div className='col-lg-4 col-md-4 col-sm-12'>
                <label className='col-form-label label fw-bold fs-5'>
                  {t('directorate.name_pa')}:
                  <i className='fa-solid fa-star-of-life text-danger align-text-top fs-9'></i>
                </label>
                <input
                  type='text'
                  name='name_pa'
                  placeholder={t('directorate.name_pa')}
                  {...formik.getFieldProps('name_pa')} // Using getFieldProps from formik
                  className={`form-control form-control-sm ${
                    renderErrorMessage('name_pa') ? `validation-error` : ''
                  }`}
                />
                {renderErrorMessage('name_pa')}
              </div>
            </div>

            <div className='row background-f5'>
              <div className='d-flex flex-stack flex-wrap pt-10'>
                <div className='fs-6 fw-bold text-gray-700 mb-5'>
                  <button
                    type='submit'
                    id='kt_sign_in_submit'
                    className='btn btn-sm btn-primary fw-bold me-2'
                    disabled={formik.isSubmitting || !formik.isValid || loading}
                  >
                    {!loading && (
                      <span className='indicator-label'>
                        <i className='fa-solid fa-plus'></i>
                        <b> {t('global.save')}</b>
                      </span>
                    )}
                    {loading && (
                      <span className='indicator-progress' style={{display: 'block'}}>
                        <b> {t('global.please-waite')}</b>
                        <span className='spinner-border spinner-border-sm align-middle ms-2'></span>
                      </span>
                    )}
                  </button>

                  <Link
                    className='btn btn-sm btn-flex btn-danger fw-bold'
                    to='/authentication/departments'
                  >
                    <b>
                      <i className='fa-solid fa-reply-all'></i>
                      {t('global.back')}
                    </b>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  )
}

export default DepartmentForm
