import {ChangeEvent, useEffect, useRef, useState} from 'react'
import {useFormik} from 'formik'
import * as Yup from 'yup'
import {Modal} from 'react-bootstrap'
import {toast} from 'react-toastify'
import {useAppDispatch, useAppSelector} from 'redux/hooks'
import {t} from 'i18next'
import {storeVehicle} from 'redux/green_zone/vehicles/VehicleSlice'
import image from '_metronic/assets/images/this.jpg'
import {getVehicleSave} from 'redux/green_zone/vehicleSave/vehicleSaveSlice'

interface VehicleCreateModalProps {
  show: boolean
  onHide: () => void
  onSuccess: () => void
}

const VehicleCreateModal = ({show, onHide, onSuccess}: VehicleCreateModalProps) => {
  const dispatch = useAppDispatch()
  const [loading, setLoading] = useState(false)
  const [frontPhoto, setFrontPhoto] = useState<File | null>(null)
  const [backPhoto, setBackPhoto] = useState<File | null>(null)
  const [platePhoto, setPlatePhoto] = useState<File | null>(null)
  const [frontPreview, setFrontPreview] = useState<string | null>(null)
  const [backPreview, setBackPreview] = useState<string | null>(null)
  const [platePreview, setPlatePreview] = useState<string | null>(null)

  // ✅ local state for select part to avoid auto-change bug
  const [platePrefix, setPlatePrefix] = useState('')

  const frontInputRef = useRef<HTMLInputElement | null>(null)
  const backInputRef = useRef<HTMLInputElement | null>(null)
  const plateInputRef = useRef<HTMLInputElement | null>(null)

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
    front_photo: Yup.mixed().required(t('validation.required', {name: t('vehicle.front_photo')})),
    back_photo: Yup.mixed().required(t('validation.required', {name: t('vehicle.back_photo')})),
    plate_photo: Yup.mixed().required(t('validation.required', {name: t('vehicle.plate_photo')})),
  })

  const initialValues = {
    vehicle_type: '',
    vehicle_color: '',
    vehicle_platte_no: '',
    vehicle_engine_no: '',
    vehicle_source: '',
    front_photo: null,
    back_photo: null,
    plate_photo: null,
  }

  const formik = useFormik({
    initialValues,
    validationSchema: FormSchema,
    onSubmit: async (values) => {
      if (!frontPhoto || !backPhoto || !platePhoto) {
        toast.error(t('vehicle.upload_all_photos'))
        return
      }

      // ✅ combine prefix + number before sending
      const finalPlate = `${platePrefix} ${values.vehicle_platte_no}`.trim()

      setLoading(true)
      const formData = new FormData()
      Object.entries(values).forEach(([key, value]) => {
        if (value !== null && !['front_photo', 'back_photo', 'plate_photo'].includes(key)) {
          if (key === 'vehicle_platte_no') {
            formData.append(key, finalPlate)
          } else {
            formData.append(key, value as string)
          }
        }
      })
      formData.append('front_photo', frontPhoto)
      formData.append('back_photo', backPhoto)
      formData.append('plate_photo', platePhoto)

      try {
        const res = await dispatch(storeVehicle(formData))
        if (res?.meta?.requestStatus === 'fulfilled') {
          toast.success(t('vehicle.toast_save'))
          onSuccess?.()
          onHide()
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
        formik.setFieldValue('front_photo', file)
      } else if (type === 'back') {
        setBackPhoto(file)
        setBackPreview(reader.result as string)
        formik.setFieldValue('back_photo', file)
      } else if (type === 'plate') {
        setPlatePhoto(file)
        setPlatePreview(reader.result as string)
        formik.setFieldValue('plate_photo', file)
      }
    }
    reader.readAsDataURL(file)
  }

  useEffect(() => {
    if (show) {
      formik.resetForm()
      setFrontPhoto(null)
      setBackPhoto(null)
      setPlatePhoto(null)
      setFrontPreview(null)
      setBackPreview(null)
      setPlatePreview(null)
      setPlatePrefix('') // reset prefix
      dispatch(getVehicleSave({per_page: 100}))
    }
  }, [show])

  return (
    <Modal show={show} onHide={onHide} size='lg' backdrop='static'>
      <Modal.Header closeButton>
        <Modal.Title>
          <h4 className='fw-bold mb-0'>
            <i className='fas fa-car text-primary me-2'></i>
            {t('vehicle.add_vehicle')}
          </h4>
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <form onSubmit={formik.handleSubmit} className='p-2'>
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

              {/* ✅ Fixed Plate Number Field */}
              <div className='col-md-12'>
                <label className='form-label fw-semibold'>{t('vehicle.vehicle_platte_no')}</label>
                <div className='input-group'>
                  <input
                    type='text'
                    className={`form-control ${
                      formik.touched.vehicle_platte_no && formik.errors.vehicle_platte_no
                        ? 'is-invalid'
                        : ''
                    }`}
                    value={formik.values.vehicle_platte_no}
                    onChange={(e) => {
                      formik.setFieldValue('vehicle_platte_no', e.target.value)
                    }}
                    placeholder={t('vehicle.vehicle_platte_no')}
                  />
                  <select
                    className='form-select'
                    style={{maxWidth: '150px'}}
                    value={platePrefix}
                    onChange={(e) => setPlatePrefix(e.target.value)}
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

          {/* ✅ Photos Section unchanged */}
          <div className='mb-4 border rounded p-3 shadow-sm bg-light'>
            <h5 className='mb-3 text-primary'>{t('vehicle.vehicle_photos')}</h5>
            <div className='row g-4'>
              {[
                {
                  type: 'front',
                  preview: frontPreview,
                  ref: frontInputRef,
                  error: formik.errors.front_photo,
                  touched: formik.touched.front_photo,
                },
                {
                  type: 'back',
                  preview: backPreview,
                  ref: backInputRef,
                  error: formik.errors.back_photo,
                  touched: formik.touched.back_photo,
                },
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
                  {photo.touched && photo.error && (
                    <div className='text-danger small mt-1'>{photo.error}</div>
                  )}
                  <div className='mt-2 fw-semibold'>{t(`vehicle.${photo.type}_photo`)}</div>
                </div>
              ))}

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
                    <span style={{color: '#777', fontSize: '1rem', userSelect: 'none'}}>
                      {t('vehicle.plate_photo')}
                    </span>
                  )}
                </div>
                {formik.touched.plate_photo && formik.errors.plate_photo && (
                  <div className='invalid-feedback d-block'>{formik.errors.plate_photo}</div>
                )}
              </div>
            </div>
          </div>

          <div className='d-flex justify-content-end'>
            <button type='submit' className='btn btn-primary px-4' disabled={loading}>
              {loading ? t('vehicle.saving') : t('vehicle.save_vehicle')}
            </button>
          </div>
        </form>
      </Modal.Body>
    </Modal>
  )
}

export default VehicleCreateModal
