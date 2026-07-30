import {ChangeEvent, useEffect, useRef, useState} from 'react'
import {useFormik} from 'formik'
import * as Yup from 'yup'
import {Modal} from 'react-bootstrap'
import {toast} from 'react-toastify'
import {useAppDispatch, useAppSelector} from 'redux/hooks'
import {t} from 'i18next'
import {updateVehicle} from 'redux/green_zone/vehicles/VehicleSlice'
import image from '_metronic/assets/images/user_male.png'
import {useSelector} from 'react-redux'
import {getVehicleSave} from 'redux/green_zone/vehicleSave/vehicleSaveSlice'

interface VehicleEditModalProps {
  showModal: boolean
  handleClose: () => void
  vehicleData: {id: number} | null
}

const VehicleEditModal = ({showModal, handleClose, vehicleData}: VehicleEditModalProps) => {
  const dispatch = useAppDispatch()
  const selector = useSelector((state: any) => state.vehicle.vehicleIndex.data)
  const [loading, setLoading] = useState(false)
  const [frontPhoto, setFrontPhoto] = useState<File | null>(null)
  const [backPhoto, setBackPhoto] = useState<File | null>(null)
  const [platePhoto, setPlatePhoto] = useState<File | null>(null)
  const [frontPreview, setFrontPreview] = useState<string | null>(null)
  const [backPreview, setBackPreview] = useState<string | null>(null)
  const [platePreview, setPlatePreview] = useState<string | null>(null)

  const frontInputRef = useRef<HTMLInputElement | null>(null)
  const backInputRef = useRef<HTMLInputElement | null>(null)
  const plateInputRef = useRef<HTMLInputElement | null>(null)

  // 🔹 get vehicle type list from redux
  const {vehicleSaveIndex: vehicleTypes} = useAppSelector((state) => state.vehicleSave)

  const FormSchema = Yup.object().shape({
    vehicle_type: Yup.string().required(
      t('validation.required', {name: t('vehicle.vehicle_type')})
    ),
    vehicle_color: Yup.string().required(
      t('validation.required', {name: t('vehicle.vehicle_color')})
    ),
    vehicle_platte_no: Yup.string().required(
      t('validation.required', {name: t('vehicle.vehicle_platte_no')})
    ),
    vehicle_engine_no: Yup.string().required(
      t('validation.required', {name: t('vehicle.vehicle_engine_no')})
    ),
    vehicle_source: Yup.string().required(
      t('validation.required', {name: t('vehicle.vehicle_source')})
    ),
  })

  const formik = useFormik({
    enableReinitialize: true,
    initialValues: {
      vehicle_type: '',
      vehicle_color: '',
      vehicle_platte_no: '',
      vehicle_engine_no: '',
      vehicle_source: '',
    },
    validationSchema: FormSchema,
    onSubmit: async (values) => {
      setLoading(true)
      const formData = new FormData()
      Object.entries(values).forEach(([key, value]) => {
        formData.append(key, value as string)
      })
      if (frontPhoto) formData.append('front_photo', frontPhoto)
      if (backPhoto) formData.append('back_photo', backPhoto)
      if (platePhoto) formData.append('plate_photo', platePhoto)

      try {
        let res
        if (vehicleData?.id !== undefined) {
          res = await dispatch(updateVehicle({id: vehicleData.id, formData}))
        } else {
          toast.error(t('vehicle.toast_error'))
        }
        if (res?.meta?.requestStatus === 'fulfilled') {
          toast.success(t('vehicle.toast_update'))
          handleClose()
        } else {
          toast.error(t('vehicle.toast_error'))
        }
      } catch {
        toast.error(t('vehicle.toast_unexpected_error'))
      } finally {
        setLoading(false)
      }
    },
  })

  const handleImageChange = (
    e: ChangeEvent<HTMLInputElement>,
    type: 'front' | 'back' | 'plate'
  ) => {
    const file = e.target.files?.[0]
    if (!file) return
    const reader = new FileReader()
    reader.onloadend = () => {
      if (type === 'front') {
        setFrontPhoto(file)
        setFrontPreview(reader.result as string)
      } else if (type === 'back') {
        setBackPhoto(file)
        setBackPreview(reader.result as string)
      } else if (type === 'plate') {
        setPlatePhoto(file)
        setPlatePreview(reader.result as string)
      }
    }
    reader.readAsDataURL(file)
  }

  useEffect(() => {
    if (!showModal || !vehicleData) return
    const loadData = async () => {
      setLoading(true)
      try {
        dispatch(getVehicleSave({per_page: 100}))

        const data = selector.filter((item: any) => item.id == vehicleData.id)
        if (data[0]) {
          formik.setValues({
            vehicle_type: data[0].vehicle_type || '',
            vehicle_color: data[0].vehicle_color || '',
            vehicle_platte_no: data[0].vehicle_platte_no || '',
            vehicle_engine_no: data[0].vehicle_engine_no || '',
            vehicle_source: data[0].vehicle_source || '',
          })
          setFrontPreview(data[0].front_photo || null)
          setBackPreview(data[0].back_photo || null)
          setPlatePreview(data[0].plate_photo || null)
        }
      } finally {
        setLoading(false)
      }
    }
    loadData()
  }, [vehicleData?.id])

  return (
    <Modal show={showModal} onHide={handleClose} size='lg' backdrop='static'>
      <Modal.Header closeButton>
        <Modal.Title>
          <h4 className='fw-bold mb-0'>
            <i className='fas fa-edit text-primary me-2'></i>
            {t('vehicle.edit_vehicle')}
          </h4>
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <form onSubmit={formik.handleSubmit} className='p-2'>
          {/* Section: Vehicle Details */}
          <div className='mb-4 border rounded p-3 shadow-sm bg-light'>
            <h5 className='mb-3 text-primary'>{t('vehicle.vehicle_details')}</h5>
            <div className='row g-3'>
              <div className='col-md-6'>
                <label className='form-label fw-semibold'>{t('vehicle.vehicle_type')}</label>
                <select
                  className={`form-select ${
                    formik.touched.vehicle_type && formik.errors.vehicle_type ? 'is-invalid' : ''
                  }`}
                  {...formik.getFieldProps('vehicle_type')}
                >
                  <option value=''>{t('global.VehicleSelect')}</option>
                  {vehicleTypes?.data?.map((item: any) => (
                    <option key={item.id} value={item.id}>
                      {item.name}
                    </option>
                  ))}
                </select>
                {formik.touched.vehicle_type && formik.errors.vehicle_type && (
                  <div className='invalid-feedback'>{formik.errors.vehicle_type}</div>
                )}
              </div>

              {[
                // {name: 'vehicle_type', label: t('vehicle.vehicle_type')},
                {name: 'vehicle_color', label: t('vehicle.vehicle_color')},
                {name: 'vehicle_engine_no', label: t('vehicle.vehicle_engine_no')},
                {name: 'vehicle_source', label: t('vehicle.vehicle_source')},
              ].map((field) => (
                <div key={field.name} className='col-md-6'>
                  <label className='form-label fw-semibold'>{field.label}</label>
                  <input
                    type='text'
                    className={`form-control ${
                      formik.touched[field.name as keyof typeof formik.values] &&
                      formik.errors[field.name as keyof typeof formik.values]
                        ? 'is-invalid'
                        : ''
                    }`}
                    {...formik.getFieldProps(field.name)}
                  />
                  {formik.touched[field.name as keyof typeof formik.values] &&
                    formik.errors[field.name as keyof typeof formik.values] && (
                      <div className='invalid-feedback'>
                        {formik.errors[field.name as keyof typeof formik.values]}
                      </div>
                    )}
                </div>
              ))}

              {/* Plate Number Field with select on right */}
              <div className='col-md-12'>
                <label className='form-label fw-semibold'>{t('vehicle.vehicle_platte_no')}</label>
                <div className='input-group'>
                  {/* Text input for plate number */}
                  <input
                    type='text'
                    className={`form-control ${
                      formik.touched.vehicle_platte_no && formik.errors.vehicle_platte_no
                        ? 'is-invalid'
                        : ''
                    }`}
                    value={formik.values.vehicle_platte_no.split(' ')[1] || ''}
                    onChange={(e) => {
                      const first = formik.values.vehicle_platte_no.split(' ')[0] || ''
                      const rest = e.target.value
                      formik.setFieldValue('vehicle_platte_no', `${first} ${rest}`.trim())
                    }}
                    placeholder={t('vehicle.vehicle_platte_no')}
                  />
                  {/* Select for first number */}
                  <select
                    className='form-select'
                    style={{maxWidth: '150px'}}
                    value={formik.values.vehicle_platte_no.split(' ')[0] || ''}
                    onChange={(e) => {
                      const first = e.target.value
                      const rest = formik.values.vehicle_platte_no.split(' ')[1] || ''
                      formik.setFieldValue('vehicle_platte_no', `${first} ${rest}`.trim())
                    }}
                  >
                    <option value=''>-- نوعیت پلیت --</option>
                    {[...Array(7)].map((_, i) => (
                      <option key={i + 1} value={i + 1}>
                        {i + 1}
                      </option>
                    ))}
                    {[...Array(7)].map((_, i) => (
                      <option key={-(i + 1)} value={-(i + 1)}>
                        {-(i + 1)}
                      </option>
                    ))}
                  </select>
                </div>
                {formik.touched.vehicle_platte_no && formik.errors.vehicle_platte_no && (
                  <div className='invalid-feedback d-block'>{formik.errors.vehicle_platte_no}</div>
                )}
              </div>
            </div>
          </div>

          {/* Section: Photos */}
          <div className='mb-4 border rounded p-3 shadow-sm bg-light'>
            <h5 className='mb-3 text-primary'>{t('vehicle.vehicle_photos')}</h5>
            <div className='row g-4'>
              {[
                {type: 'front', preview: frontPreview, ref: frontInputRef},
                {type: 'back', preview: backPreview, ref: backInputRef},
              ].map((photo, idx) => (
                <div key={idx} className='col-md-6 text-center'>
                  <input
                    type='file'
                    hidden
                    accept='image/*'
                    onChange={(e) => handleImageChange(e, photo.type as 'front' | 'back')}
                    ref={photo.ref}
                  />
                  <div
                    className='border rounded p-2 bg-white shadow-sm'
                    style={{cursor: 'pointer', display: 'inline-block'}}
                    onClick={() => photo.ref.current?.click()}
                  >
                    <img
                      src={photo.preview || image}
                      alt={`${photo.type}-photo`}
                      className='img-fluid rounded'
                      style={{width: '180px', height: '180px', objectFit: 'cover'}}
                    />
                  </div>
                  <div className='mt-2 fw-semibold'>{t(`vehicle.${photo.type}_photo`)}</div>
                </div>
              ))}
              {/* Plate photo upload */}
              <div className='col-md-12 text-center'>
                <input
                  type='file'
                  hidden
                  accept='image/*'
                  onChange={(e) => handleImageChange(e, 'plate')}
                  ref={plateInputRef}
                />
                <div
                  className='border rounded p-2 bg-white shadow-sm'
                  style={{
                    cursor: 'pointer',
                    display: 'inline-flex',
                    justifyContent: 'center',
                    alignItems: 'center',
                    width: '320px',
                    height: '100px',
                    overflow: 'hidden',
                    backgroundColor: '#fff',
                  }}
                  onClick={() => plateInputRef.current?.click()}
                >
                  {platePreview ? (
                    <img
                      src={platePreview}
                      alt='plate-photo'
                      style={{
                        maxWidth: '100%',
                        maxHeight: '100%',
                        objectFit: 'contain',
                      }}
                    />
                  ) : (
                    <span
                      style={{
                        color: '#777',
                        fontSize: '1rem',
                        userSelect: 'none',
                      }}
                    >
                      {t('vehicle.plate_photo')}
                    </span>
                  )}
                </div>
              </div>
            </div>
          </div>

          {/* Submit */}
          <div className='d-flex justify-content-end'>
            <button type='submit' className='btn btn-primary px-4' disabled={loading}>
              {loading ? t('global.saving') : t('global.update')}
            </button>
          </div>
        </form>
      </Modal.Body>
    </Modal>
  )
}

export default VehicleEditModal
