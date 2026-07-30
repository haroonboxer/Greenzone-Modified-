import {useEffect, useState} from 'react'
import {useNavigate, useParams} from 'react-router-dom'
import {FormikErrors, FormikTouched, useFormik} from 'formik'
import * as Yup from 'yup'
import axios from 'axios'
import {toast} from 'react-toastify'
import {updateDepartment} from '../services'
import EditDepartmentForm from './EditDepartmentForm'
import Loader from '../../../../../pages/loading/Loader'
import {useTranslation} from 'react-i18next'

const EditDepartment = () => {
  const {t} = useTranslation()
  const [directorates, setDirectorates] = useState([])
  const [loading, setLoading] = useState(false)
  const [loader, setLoader] = useState(true)
  const navigate = useNavigate()
  const {id} = useParams()
  const validationMessages = {
    required: (name: string) => t('validation.required', {name: t(name)}),
    max: (name: string) => t('validation.max', {name: t(name)}),
  }

  const FormSchema = Yup.object().shape({
    name_fa: Yup.string()
      .max(64, validationMessages.max('directorate.name_fa'))
      .required(validationMessages.required('directorate.name_fa')),
    name_pa: Yup.string()
      .max(64, validationMessages.max('directorate.name_pa'))
      .required(validationMessages.required('directorate.name_pa')),
    directorate_id: Yup.number().required(validationMessages.required('directorate.name_en')),
  })

  const initialValues = {
    name_fa: '',
    name_pa: '',
    directorate_id: '',
  }

  const formik = useFormik({
    initialValues,
    validationSchema: FormSchema,
    onSubmit: async (values, {setStatus, setSubmitting, resetForm}) => {
      setLoading(true)
      try {
        const response = await updateDepartment(
          id as string,
          values.name_fa,
          values.name_pa,
          values.directorate_id
        )
        resetForm()
        navigate('/authentication/departments')
        const message = response.data.message
        toast.success(<p className='fs-4 fw-bold'>{message}</p>)
      } catch (error: any) {
        setLoading(false)
        setSubmitting(false)
        if (error.response && error.response.status === 422) {
          const validationErrors = error.response.data.errors
          Object.values(validationErrors).forEach((errorArray) => {
            if (Array.isArray(errorArray)) {
              errorArray.forEach((errorMessage) => {
                toast.error(<p className='fs-4 fw-bold'>{errorMessage}</p>)
              })
            }
          })
        } else {
          // console.error(error)
          setStatus('The registration details are incorrect')
        }
      }
    },
  })

  const renderErrorMessage = (fieldName: keyof typeof initialValues) => {
    const errors = formik.errors as FormikErrors<typeof initialValues>
    const touched = formik.touched as FormikTouched<typeof initialValues>
    if (errors[fieldName] && touched[fieldName]) {
      return (
        <div className='fv-plugins-message-container'>
          <span role='alert' className='text-danger fw-bold'>
            {errors[fieldName]}
          </span>
        </div>
      )
    }
    return null
  }

  const fetchDirectorates = async () => {
    try {
      const response = await axios.get('api/department/edit/' + id)
      setDirectorates(response.data.directorates)
      const {name_da, name_pa, directorate_id} = response.data.department
      formik.setValues({name_fa: name_da, name_pa, directorate_id})
      setLoader(false)
    } catch (error: any) {
      console.error('Error fetching directorates:', error)
      toast.error(<p className='fs-4 fw-bold'>{error}</p>)
    }
  }

  useEffect(() => {
    fetchDirectorates()
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  return (
    <>
      {!loader ? (
        <EditDepartmentForm
          formik={formik}
          directorates={directorates}
          renderErrorMessage={renderErrorMessage}
          t={toast}
          loading={loading}
        />
      ) : (
        <div className='d-flex justify-content-center'>
          <Loader />
        </div>
      )}
    </>
  )
}

export default EditDepartment
