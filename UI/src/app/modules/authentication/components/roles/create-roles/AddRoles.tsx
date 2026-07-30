import { useEffect, useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { FormikErrors, FormikTouched, useFormik } from 'formik'
import * as Yup from 'yup'
import AddRolesForm from './AddRolesForm'
import { useAppDispatch, useAppSelector } from '../../../../../../redux/hooks'
import {
  createRole,
  getPermissionsBySystemId,
  getSystems,
} from '../../../../../../redux/authentication/roles/roleSlice'
import { toast } from 'react-toastify'
import { useTranslation } from 'react-i18next'

interface FormValues {
  name: string
  system_id: string
}

const AddRoles = () => {
  const { t } = useTranslation()
  const [permissions, setPermissions] = useState<any[]>([])
  const [selectedCheckboxes, setSelectedCheckboxes] = useState<number[]>([])
  const [loading, setLoading] = useState(false)
  const [pageLoader, setPageLoader] = useState(false)
  const navigate = useNavigate()

  const dispatch = useAppDispatch()
  const { systems } = useAppSelector((state) => state.systems)

  const validationMessages = {
    required: (name: string) => t('validation.required', { name: t(name) }),
    max: (name: string) => t('validation.max', { name: t(name) }),
    required_select: (name: string) => t('validation.select-required', { name: t(name) }),
  }

  const FormSchema = Yup.object().shape({
    name: Yup.string()
      .max(64, validationMessages.max('user.roleName'))
      .required(validationMessages.required('user.roleName')),
    system_id: Yup.string().required(validationMessages.required_select('user.system')),
  })

  const initialValues: FormValues = {
    name: '',
    system_id: '',
  }

  const formik = useFormik({
    initialValues,
    validationSchema: FormSchema,
    onSubmit: async (values, { resetForm }) => {
      setLoading(true)
      try {
        const formData = createFormData(values)
        const response = await dispatch(createRole(formData) as any)

        if (createRole.fulfilled.match(response)) {
          handleFulfilledResponse(response)
          resetForm()
          setSelectedCheckboxes([])
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

  // const createFormData = (values: FormValues) => {
  //   const { name, system_id } = values
  //   const formData = new FormData()
  //   formData.append('name', name)
  //   formData.append('system_id', system_id)
  //   formData.append('permissions', JSON.stringify(selectedCheckboxes))
  //   return formData
  // }

  const createFormData = (values: FormValues) => {
    const formData = new FormData();
    formData.append('name', values.name);
    formData.append('system_id', values.system_id);
    formData.append('permissions', JSON.stringify(selectedCheckboxes));
    return formData;
  }


  const handleFulfilledResponse = (response: any) => {
    const { payload } = response
    toast.success(<p className='fs-4 fw-bold'>{payload.message}</p>)
    navigate('/authentication/roles')
  }

  const handleRejectedResponse = (response: any) => {
    const { payload } = response
    toast.error(<p className='fs-4 fw-bold'>{payload}</p>)
  }

  const handleError = (error: any) => {
    console.error('Error creating role:', error)
    toast.error(<p className='fs-4 fw-bold'>{t('validation.errorOccurred')}</p>)
  }

  const renderErrorMessage = (fieldName: keyof FormValues) => {
    const errors = formik.errors as FormikErrors<FormValues>
    const touched = formik.touched as FormikTouched<FormValues>
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

  useEffect(() => {
    dispatch(getSystems())
  }, [dispatch])

  const systemChangeHandler = async (system: any) => {
    if (system.value) {
      setPageLoader(true)
      setSelectedCheckboxes([]) // Clear previous selections
      formik.setFieldValue('system_id', system.value)

      try {
        const response = await dispatch(getPermissionsBySystemId(system.value))
        if (getPermissionsBySystemId.fulfilled.match(response)) {
          setPermissions(response.payload)
        }
      } catch (error) {
        console.error('Error fetching permissions:', error)
      } finally {
        setPageLoader(false)
      }
    }
  }

  const handleSelectAll = (event: React.ChangeEvent<HTMLInputElement>) => {
    if (event.target.checked) {
      const allCheckboxIds = permissions.map((p) => p.id)
      setSelectedCheckboxes(allCheckboxIds)
    } else {
      setSelectedCheckboxes([])
    }
  }

  const handleCheckboxChange = (
    event: React.ChangeEvent<HTMLInputElement>,
    permissionId: number
  ) => {
    setSelectedCheckboxes(prev =>
      event.target.checked
        ? [...prev, permissionId]
        : prev.filter(id => id !== permissionId)
    )
  }

  return (
    <AddRolesForm
      formik={formik}
      systems={systems}
      renderErrorMessage={renderErrorMessage}
      t={t}
      loading={loading}
      systemChangeHandler={systemChangeHandler}
      permissions={permissions}
      handleSelectAll={handleSelectAll}
      handleCheckboxChange={handleCheckboxChange}
      selectedCheckboxes={selectedCheckboxes}
      pageLoader={pageLoader}
    />
  )
}

export default AddRoles