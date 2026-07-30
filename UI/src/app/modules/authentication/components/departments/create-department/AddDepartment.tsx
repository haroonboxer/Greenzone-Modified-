import {useState} from 'react'
import {useNavigate, useParams} from 'react-router-dom'
import {FormikErrors, FormikTouched, useFormik} from 'formik'
import * as Yup from 'yup'
import {toast} from 'react-toastify'
import DepartmentForm from './DepartmentForm'
import {createDepartment} from '../../../../../../redux/authentication/department/departmentSlice'
import {useDispatch} from 'react-redux'
import {useTranslation} from 'react-i18next'

const AddDepartment = () => {
  const {t} = useTranslation()
  const [loading, setLoading] = useState(false)
  const navigate = useNavigate()
  const {id} = useParams()
  const validationMessages = {
    required: (name: string) => t('validation.required', {name: t(name)}),
    max: (name: string) => t('validation.max', {name: t(name)}),
  }
  const dispatch = useDispatch()

  const FormSchema = Yup.object().shape({
    name_fa: Yup.string()
      .max(64, validationMessages.max('directorate.name_fa'))
      .required(validationMessages.required('directorate.name_fa')),
    name_pa: Yup.string()
      .max(64, validationMessages.max('directorate.name_pa'))
      .required(validationMessages.required('directorate.name_pa')),
    name_en: Yup.string()
      .max(64, validationMessages.max('directorate.name_en'))
      .required(validationMessages.required('directorate.name_en')),
  })

  const initialValues = {
    name_fa: '',
    name_pa: '',
    name_en: '',
  }

  const formik = useFormik({
    initialValues,
    validationSchema: FormSchema,
    onSubmit: async (values, {resetForm}) => {
      setLoading(true)
      try {
        const formData = createFormData(values)
        const response = await dispatch(createDepartment(formData) as any)

        if (createDepartment.fulfilled.match(response)) {
          handleFulfilledResponse(response)
          resetForm()
        } else {
          handleRejectedResponse(response)
        }
      } catch (error) {
        handleError(error)
      } finally {
        setLoading(false)
      }
    },
  })

  const createFormData = (values: any) => {
    const {name_fa, name_pa, name_en} = values
    const formData = new FormData()
    formData.append('name_da', name_fa)
    formData.append('name_pa', name_pa)
    formData.append('name_en', name_en)
    formData.append('parent_id', id as string)
    return formData
  }

  const handleFulfilledResponse = (response: any) => {
    const {meta, payload} = response
    if (meta.requestStatus === 'fulfilled') {
      toast.success(<p className='fs-4 fw-bold'>{payload.message}</p>)
      navigate('/authentication/departments')
    } else {
      toast.error(<p className='fs-4 fw-bold'>{t('validation.required')}</p>)
    }
    setLoading(false)
  }

  const handleRejectedResponse = (response: any) => {
    const {payload} = response
    toast.error(<p className='fs-4 fw-bold'>{payload}</p>)
    setLoading(false)
  }

  const handleError = (error: any) => {
    console.error('Error creating department:', error.message)
    setLoading(false)
  }

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

  return (
    <DepartmentForm
      formik={formik}
      renderErrorMessage={renderErrorMessage}
      t={t}
      loading={loading}
    />
  )
}

export default AddDepartment
