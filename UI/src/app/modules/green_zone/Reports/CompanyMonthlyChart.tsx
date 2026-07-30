import React, {useEffect} from 'react'
import {useDispatch, useSelector} from 'react-redux'
import Chart from 'react-apexcharts'
import {ApexOptions} from 'apexcharts'
import {AppDispatch} from 'redux/store'
import {fetchMonthlyCompanyStats} from 'redux/green_zone/reports/reportSlice'

const CompanyMonthlyChart = () => {
  const dispatch = useDispatch<AppDispatch>()
  const {monthlyStats, loading, error} = useSelector((state: any) => state.report)

  useEffect(() => {
    dispatch(fetchMonthlyCompanyStats())
  }, [dispatch])

  if (loading) return <div>در حال بارگذاری...</div>
  if (error) return <div>خطا: {error}</div>

  const monthNames = [
    'حمل',
    'ثور',
    'جوزا',
    'سرطان',
    'اسد',
    'سنبله',
    'میزان',
    'عقرب',
    'قوس',
    'جدی',
    'دلو',
    'حوت',
  ]

  // Typed for safety
  const chartData: {x: string; y: number}[] = monthlyStats.map((item: any) => ({
    x: `${monthNames[item.month - 1]} ${item.year}`,
    y: item.count,
  }))

  const options: ApexOptions = {
    chart: {
      type: 'bar',
      height: 400,
      toolbar: {show: false},
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '50%',
        borderRadius: 6,
      },
    },
    dataLabels: {
      enabled: false,
    },
    fill: {
      type: 'gradient',
      gradient: {
        shade: 'light',
        type: 'vertical',
        shadeIntensity: 0.5,
        gradientToColors: ['#00c6ff', '#f77062'],
        inverseColors: false,
        opacityFrom: 0.9,
        opacityTo: 0.7,
        stops: [0, 100],
      },
    },
    xaxis: {
      categories: chartData.map((item: {x: string}) => item.x),
      title: {
        text: 'ماه',
        style: {
          fontSize: '14px',
          fontWeight: 600,
        },
      },
      labels: {
        style: {
          fontSize: '13px',
        },
      },
    },
    yaxis: {
      title: {
        text: 'تعداد شرکت‌ها',
        style: {
          fontSize: '14px',
          fontWeight: 600,
        },
      },
    },
    tooltip: {
      theme: 'light',
      y: {
        formatter: (val: number) => `${val} شرکت`,
      },
    },
    title: {
      text: 'آمار ثبت شرکت‌ها به تفکیک ماه',
      align: 'center',
      style: {
        fontSize: '18px',
        fontWeight: 'bold',
      },
    },
    grid: {
      borderColor: '#e0e0e0',
      strokeDashArray: 4,
    },
    legend: {
      show: false,
    },
  }

  const series = [
    {
      name: 'شرکت‌ها',
      data: chartData.map((item: {y: number}) => item.y),
    },
  ]

  return (
    <div className='bg-white p-6 rounded-xl shadow-md max-w-5xl mx-auto my-6'>
      <Chart options={options} series={series} type='bar' height={400} />
    </div>
  )
}

export default CompanyMonthlyChart
