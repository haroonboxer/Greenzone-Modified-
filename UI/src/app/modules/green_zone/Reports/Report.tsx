import React, {useEffect, useState} from 'react'
import {useDispatch, useSelector} from 'react-redux'
import {AppDispatch, RootState} from 'redux/store'
import DatePicker, {DateObject} from 'react-multi-date-picker'
import persian from 'react-date-object/calendars/persian'
import {t} from 'i18next'
import Select, {SingleValue, ActionMeta} from 'react-select'
import makeAnimated from 'react-select/animated'
import persian_fa from 'helpers/persian_fa'
import {fetchCompanies, fetchReport} from 'redux/green_zone/reports/reportSlice'
import axios from 'axios'
import Swal from 'sweetalert2'

const animatedComponents = makeAnimated()

interface VehicleOption {
  value: string
  label: string
}

interface ReportItem {
  title: string
  value: number | string
}

const toEnglishDigits = (str: string): string =>
  str.replace(/[۰-۹]/g, (d) => String.fromCharCode(d.charCodeAt(0) - 1728))

const Report = () => {
  const dispatch = useDispatch<AppDispatch>()
  const {reportIndex, companies, loading, error} = useSelector((state: RootState) => state.report)

  const [startDate, setStartDate] = useState<string>('')
  const [endDate, setEndDate] = useState<string>('')
  const [selectedVehicle, setSelectedVehicle] = useState<SingleValue<VehicleOption>>(null)

  useEffect(() => {
    dispatch(fetchReport({}))
    dispatch(fetchCompanies())
  }, [dispatch])

  //=========================================================================================================\
  // Excel Report Download
  //=========================================================================================================

  const downloadExcel = () => {
    const queryParams = new URLSearchParams()
    if (startDate) queryParams.append('start_date', toEnglishDigits(startDate))
    if (endDate) queryParams.append('end_date', toEnglishDigits(endDate))
    if (selectedVehicle) queryParams.append('vehicle_id', selectedVehicle.value)
    try {
      axios({
        url: `api/report/generate-report`,
        method: 'GET',
        responseType: 'blob',
        headers: {
          Accept: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        },
        params: {
          ...Object.fromEntries(queryParams.entries()),
        },
      })
        .then((response) => {
          const url = window.URL.createObjectURL(new Blob([response.data]))
          const link = document.createElement('a')
          link.href = url

          let fileName = 'ExcelReport'
          if (selectedVehicle) {
            fileName = `${selectedVehicle.label}_${fileName}`
          }
          if (startDate && endDate) {
            fileName = `${fileName}_from_${startDate}_to_${endDate}`
          }

          link.setAttribute('download', `${fileName}.xlsx`)
          document.body.appendChild(link)
          link.click()
          document.body.removeChild(link)
        })
        .catch((error) => {
          console.error('Error downloading Excel:', error)
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to download Excel file',
          })
        })
    } catch (error) {
    
    }
  }
  //=========================================================================================================

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    const params: any = {}

    if (startDate) params.start_date = toEnglishDigits(startDate)
    if (endDate) params.end_date = toEnglishDigits(endDate)
    if (selectedVehicle) params.vehicle_id = selectedVehicle.value

    dispatch(fetchReport(params))
  }

  const handleReset = () => {
    setStartDate('')
    setEndDate('')
    setSelectedVehicle(null)
    dispatch(fetchReport({}))
  }

  const handleCompanyChange = (
    newValue: SingleValue<VehicleOption>,
    actionMeta: ActionMeta<VehicleOption>
  ) => {
    setSelectedVehicle(newValue)
    const params: any = {}
    if (newValue) {
      params.vehicle_id = newValue.value
    }
    if (startDate) params.start_date = toEnglishDigits(startDate)
    if (endDate) params.end_date = toEnglishDigits(endDate)
    dispatch(fetchReport(params))
  }

  const vehicleOptions: VehicleOption[] = companies.map((company: any) => ({
    value: company.id.toString(),
    label: company.name,
  }))

  const reportItems: ReportItem[] = reportIndex?.data
    ? [
        {title: t('global.totalRegisteredVehicles'), value: reportIndex.data.total_companies},
        {title: t('global.totalRegisteredLicenses'), value: reportIndex.data.total_license},
        {title: t('global.totalNewLicenses'), value: reportIndex.data.total_new_license},
        {title: t('global.totalExtendedLicenses'), value: reportIndex.data.total_extend_license},
        {title: t('global.totalDuplicateLicenses'), value: reportIndex.data.total_renew_license},
        // { title: t('global.totalActiveLicenses'), value: reportIndex.data.active_licenses },
        {title: t('global.totalInactiveLicenses'), value: reportIndex.data.non_active_licenses},
      ]
    : []

  return (
    <div className='card mb-5 shadow-lg p-3 bg-body rounded'>
      <div className='card-header'>
        <h3 className='fw-bolder'>
          <i className='fas fa-search text-primary me-2'></i>
          {t('global.list', {name: t('global.report')})}
        </h3>
      </div>
      <div className='card-body'>
        <form onSubmit={handleSubmit} className='row g-3 mb-4'>
          <div className='col-md-3'>
            <label className='form-label'> {t('global.vehicleNameBased')}</label>
            <Select
              className='basic-single'
              classNamePrefix='select'
              isClearable={true}
              isSearchable={true}
              name='company'
              options={vehicleOptions}
              value={selectedVehicle}
              onChange={handleCompanyChange}
              placeholder={t('actions.selectOne')}
              loadingMessage={() => 'در حال بارگذاری...'}
              components={animatedComponents}
              isMulti={false}
              styles={{
                control: (base) => ({
                  ...base,
                  height: '38px',
                  minHeight: '38px',
                }),
              }}
            />
          </div>

          <div className='col-md-3'>
            <label className='form-label'>{t('printedCard.select_start_date')} </label>
            <DatePicker
              calendar={persian}
              locale={persian_fa}
              value={startDate}
              placeholder={t('actions.select')}
              format='YYYY/MM/DD'
              onChange={(date) => {
                if (Array.isArray(date)) return
                const formatted = (date as DateObject)?.format('YYYY/MM/DD')
                setStartDate(formatted || '')
              }}
              containerStyle={{width: '100%', direction: 'rtl'}}
              style={{
                width: '100%',
                height: '38px',
                fontSize: '1.2rem',
                color: '#153a81',
                fontWeight: 'bold',
                padding: '12px',
              }}
              editable={true}
            />
          </div>

          <div className='col-md-3'>
            <label className='form-label'> {t('printedCard.select_end_date')} </label>
            <DatePicker
              calendar={persian}
              locale={persian_fa}
              value={endDate}
              placeholder={t('actions.select')}
              format='YYYY/MM/DD'
              onChange={(date) => {
                if (Array.isArray(date)) return
                const formatted = (date as DateObject)?.format('YYYY/MM/DD')
                setEndDate(formatted || '')
              }}
              containerStyle={{width: '100%', direction: 'rtl'}}
              style={{
                width: '100%',
                height: '38px',
                fontSize: '1.2rem',
                color: '#153a81',
                fontWeight: 'bold',
                padding: '12px',
              }}
              editable={true}
            />
          </div>

          <div className='col-md-1 d-flex align-items-end'>
            <button type='submit' className='btn btn-primary w-100'>
              <i className='fas fa-eye'></i>&nbsp;{t('global.viewReport')}
            </button>
          </div>

          <div className='col-md-1 d-flex align-items-end'>
            <button type='button' className='btn btn-success w-100' onClick={downloadExcel}>
              <i className='fas fa-download'></i>&nbsp;{t('global.downloadReport')}
            </button>
          </div>

          <div className='col-md-1 d-flex align-items-end'>
            <button type='button' className='btn btn-warning w-100' onClick={handleReset}>
              <i className='fas fa-undo'></i>&nbsp;{t('global.reset')}
            </button>
          </div>
        </form>

        {loading && (
          <div className='d-flex justify-content-center'>
            <div className='spinner-border text-primary' role='status'>
              <span className='visually-hidden'>Loading...</span>
            </div>
          </div>
        )}
        {error && <p className='text-danger'>Error: {error}</p>}

        {reportItems.length > 0 && (
          <div className='row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mt-4'>
            {reportItems.map((item, index) => (
              <div className='col' key={index}>
                <div className='card card-xl-stretch dashboard-item h-100'>
                  <div className='card-header border-0'>
                    <h3 className='card-title fw-bold text-primary'>{item.title}</h3>
                    <div className='card-toolbar'>
                      <span className='badge badge-light-primary fs-2 fw-semibold'>
                        {item.value}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  )
}

export default Report
