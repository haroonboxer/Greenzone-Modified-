import { ChangeEvent, useEffect, useRef, useState } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { useAppDispatch } from 'redux/hooks'
import { toast } from 'react-toastify'
import { FileUploader } from 'react-drag-drop-files'
import * as Yup from 'yup'
import { useFormik } from 'formik'
import { provinces, districts } from 'helpers/provincesAndDistrictsJson'
import image from '_metronic/assets/images/user_male.png'
import { t } from 'i18next'
import { Modal, Card } from 'react-bootstrap'
import { storeDriver } from 'redux/green_zone/driver/driverSlice'

interface DriverCreateModalProps {
  show: boolean
  onHide: () => void
  onSuccess: () => void
}

const DriverCreateModal = ({ show, onHide, onSuccess }: DriverCreateModalProps) => {
  const [loading, setLoading] = useState(false)
  const [files, setFiles] = useState<File[]>([])
  const [photo, setPhoto] = useState<File | null>(null)
  const [selectedDistricts, setSelectedDistricts] = useState<any[]>([])
  const [selectedCurrentDistricts, setSelectedCurrentDistricts] = useState<any[]>([])
  const navigate = useNavigate()
  const dispatch = useAppDispatch()
  const { id } = useParams<{ id: string }>()

  const validationMessages = {
    required: (name: string) => `${name} ${t('driver.is_required')}`,
  }

  const FormSchema = Yup.object().shape({
    name: Yup.string().required(validationMessages.required(t('driver.name'))),
    f_name: Yup.string().required(validationMessages.required(t('driver.f_name'))),
    g_f_name: Yup.string().required(validationMessages.required(t('driver.g_f_name'))),
    phone: Yup.string().required(validationMessages.required(t('driver.phone'))),
    nic: Yup.string().required(validationMessages.required(t('driver.nic'))),
    main_province: Yup.string().required(validationMessages.required(t('driver.main_province'))),
    main_district: Yup.string().required(validationMessages.required(t('driver.main_district'))),
    main_village: Yup.string().required(validationMessages.required(t('driver.main_village'))),
    current_province: Yup.string().required(validationMessages.required(t('driver.current_province'))),
    current_district: Yup.string().required(validationMessages.required(t('driver.current_district'))),
    current_village: Yup.string().required(validationMessages.required(t('driver.current_village'))),
    photo: Yup.mixed().required(validationMessages.required(t('driver.photo'))),
  })

  const initialValues = {
    name: '',
    f_name: '',
    g_f_name: '',
    phone: '',
    nic: '',
    main_province: '',
    main_district: '',
    main_village: '',
    current_province: '',
    current_district: '',
    current_village: '',
    photo: null,
  }

  const formik = useFormik({
    initialValues,
    validationSchema: FormSchema,
    onSubmit: async (values) => {
      setLoading(true)
      const formData = new FormData()
      Object.entries(values).forEach(([key, value]) => {
        if (value !== null && key !== 'photo') {
          formData.append(key, value)
        }
      })

      if (photo) {
        formData.append('photo', photo)
      } else {
        toast.error(t('driver.photo_required'))
        setLoading(false)
        return
      }
      if (id) {
        formData.append('vehicle_id', id)
      }

      files.forEach((file) => formData.append('attachments[]', file))
      try {
        const response = await dispatch(storeDriver(formData))
        if (response?.meta?.requestStatus === 'fulfilled') {
          toast.success(t('driver.toast_save'))
          onSuccess?.()
          onHide()
        } else {
          toast.error(t('driver.toast_error_driver'))
        }
      } catch {
        toast.error(t('driver.toast_error_driver'))
      } finally {
        setLoading(false)
      }
    },
  })

  useEffect(() => {
    if (show) {
      formik.resetForm()
      setFiles([])
      setPhoto(null)
      setSelectedDistricts([])
      setSelectedCurrentDistricts([])
      setImagePreview(null)
    }
  }, [show])

  const handleProvinceChange = (provinceId: string) => {
    setSelectedDistricts(districts.filter(d => String(d.provincecode) === provinceId))
  }

  const handleCurrentProvinceChange = (provinceId: string) => {
    setSelectedCurrentDistricts(districts.filter(d => String(d.provincecode) === provinceId))
  }

  const handleDrop = (fileList: File[]) => setFiles([...files, ...fileList])
  const handleFileRemove = (index: number) => setFiles(files.filter((_, i) => i !== index))

  const [imagePreview, setImagePreview] = useState<string | null>(null)
  const fileInputRef = useRef<HTMLInputElement | null>(null)

  const handleImageChange = (e: ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files ? e.target.files[0] : null
    if (file) {
      setPhoto(file)
      formik.setFieldValue('photo', file)
      const reader = new FileReader()
      reader.onloadend = () => setImagePreview(reader.result as string)
      reader.readAsDataURL(file)
    }
  }

  const defaultImage = image

  return (
    <Modal show={show} onHide={onHide} size='xl' backdrop='static'>
      <Modal.Header closeButton>
        <Modal.Title>
          <h2 className='fw-bold m-0'>
            <i className='fas fa-plus text-primary'></i> {t('driver.add_driver')}
          </h2>
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
        <form onSubmit={formik.handleSubmit}>
          {/* Basic Info */}
          <div className='col-md-12'>
            <div className="row">
              <div className='col-md-9'>
                <div className='row mb-5'>
                  <div className='col-md-6'>
                    <label className='form-label'>{t('driver.name')}</label>
                    <input type='text' className='form-control' {...formik.getFieldProps('name')} />
                    {formik.touched.name && formik.errors.name && <div className='text-danger'>{formik.errors.name}</div>}
                  </div>
                  <div className='col-md-6'>
                    <label className='form-label'>{t('driver.f_name')}</label>
                    <input type='text' className='form-control' {...formik.getFieldProps('f_name')} />
                    {formik.touched.f_name && formik.errors.f_name && <div className='text-danger'>{formik.errors.f_name}</div>}
                  </div>
                </div>
                <div className='row mb-5'>
                  <div className='col-md-6'>
                    <label className='form-label'>{t('driver.phone')}</label>
                    <input type='text' className='form-control' {...formik.getFieldProps('phone')} />
                    {formik.touched.phone && formik.errors.phone && <div className='text-danger'>{formik.errors.phone}</div>}
                  </div>
                  <div className='col-md-6'>
                    <label className='form-label'>{t('driver.g_f_name')}</label>
                    <input type='text' className='form-control' {...formik.getFieldProps('g_f_name')} />
                    {formik.touched.g_f_name && formik.errors.g_f_name && <div className='text-danger'>{formik.errors.g_f_name}</div>}
                  </div>
                </div>
                <div className='row mb-5'>
                  <div className='col-md-12'>
                    <label className='form-label'>{t('driver.nic')}</label>
                    <input type='text' className='form-control' {...formik.getFieldProps('nic')} />
                    {formik.touched.nic && formik.errors.nic && <div className='text-danger'>{formik.errors.nic}</div>}
                  </div>
                </div>
              </div>
              <div className='col-md-3'>
                <label className='form-label d-block'>{t('driver.photo')}</label>
                <input id='photo' type='file' hidden accept='image/png, image/jpeg'
                  onChange={handleImageChange} ref={fileInputRef} />
                <Card className='shadow-sm p-5 badge' style={{ cursor: 'pointer' }} onClick={() => fileInputRef.current?.click()}>
                  <img src={imagePreview || defaultImage} className='img-fluid rounded' alt='user'
                    style={{ width: '200px', height: '200px', objectFit: 'cover', textAlign: 'center' }} />
                </Card>
                {formik.touched.photo && formik.errors.photo && <div className='text-danger mt-1'>{formik.errors.photo}</div>}
              </div>
            </div>
          </div>

          {/* Main Residence */}
          <h5 className='fw-bold mb-3 text-primary'>{t('driver.main_residence')}</h5>
          <div className='row mb-4'>
            <div className='col-md-4'>
              <label>{t('driver.main_province')}</label>
              <select className='form-control' {...formik.getFieldProps('main_province')}
                onChange={(e) => { formik.handleChange(e); handleProvinceChange(e.target.value) }}>
                <option value=''>{t('driver.select_province')}</option>
                {provinces.map((p) => <option key={p.value} value={p.value}>{p.label}</option>)}
              </select>
            </div>
            <div className='col-md-4'>
              <label>{t('driver.main_district')}</label>
              <select className='form-control' {...formik.getFieldProps('main_district')}>
                <option value=''>{t('driver.select_district')}</option>
                {selectedDistricts.map((d) => <option key={d.value} value={d.value}>{d.label}</option>)}
              </select>
            </div>
            <div className='col-md-4'>
              <label>{t('driver.main_village')}</label>
              <input type='text' className='form-control' {...formik.getFieldProps('main_village')} />
            </div>
          </div>

          {/* Current Residence */}
          <h5 className='fw-bold mb-3 text-primary'>{t('driver.current_residence')}</h5>
          <div className='row mb-4'>
            <div className='col-md-4'>
              <label>{t('driver.current_province')}</label>
              <select className='form-control' {...formik.getFieldProps('current_province')}
                onChange={(e) => { formik.handleChange(e); handleCurrentProvinceChange(e.target.value) }}>
                <option value=''>{t('driver.select_province')}</option>
                {provinces.map((p) => <option key={p.value} value={p.value}>{p.label}</option>)}
              </select>
            </div>
            <div className='col-md-4'>
              <label>{t('driver.current_district')}</label>
              <select className='form-control' {...formik.getFieldProps('current_district')}>
                <option value=''>{t('driver.select_district')}</option>
                {selectedCurrentDistricts.map((d) => <option key={d.value} value={d.value}>{d.label}</option>)}
              </select>
            </div>
            <div className='col-md-4'>
              <label>{t('driver.current_village')}</label>
              <input type='text' className='form-control' {...formik.getFieldProps('current_village')} />
            </div>
          </div>

          {/* Attachments */}
          <div className='mb-4 p-3 border rounded bg-light'>
            <legend className='fs-6 fw-bold'>
              <i className='fas fa-paperclip text-primary me-2'></i> {t('driver.upload')}
            </legend>
            <FileUploader multiple handleChange={handleDrop} onDrop={handleDrop} name='file'
              maxSize={30} label={t('driver.upload_files')} />
            <ul className='mt-2'>
              {files.map((file, index) => (
                <li key={index}>
                  {file.name}{' '}
                  <i className='fas fa-times text-danger ms-2 cursor-pointer'
                    onClick={() => handleFileRemove(index)}></i>
                </li>
              ))}
            </ul>
          </div>

          {/* Buttons */}
          <div className='d-flex justify-content-between'>
            <button type='submit' className='btn btn-primary' disabled={loading}>
              {loading ? t('driver.saving') : t('driver.save_driver')}
            </button>
            <button className='btn btn-danger' type='button' disabled={loading} onClick={() => navigate(-1)}>
              {t('driver.back')}
            </button>
          </div>
        </form>
      </Modal.Body>
    </Modal>
  )
}

export default DriverCreateModal
