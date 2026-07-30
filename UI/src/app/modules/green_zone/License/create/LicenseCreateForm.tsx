import React, { useState, useEffect } from 'react'
import { useFormik } from 'formik'
import * as Yup from 'yup'
import { useTranslation } from 'react-i18next'
import type { DateObject } from 'react-multi-date-picker'
import { FileUploader } from 'react-drag-drop-files'
import { License } from '../__model'
import {
  Modal,
  Button,
  Form,
  Row,
  Col,
  Spinner,
  FormControl,
  FormGroup,
  FormLabel,
  FormSelect,
} from 'react-bootstrap'

interface LicenseCreateFormProps {
  showModal: boolean
  handleClose: () => void
  handleSubmit: (values: License) => Promise<void>
  initialData: License
  loading: boolean
  handleDrop: (files: File[]) => void
  handleFileRemove: (index: number) => void
  fileType: string[]
  files: File[]
  persian_fa: any
  DatePicker: any
  persian: any
}

const LicenseCreateForm: React.FC<LicenseCreateFormProps> = ({
  showModal,
  handleClose,
  handleSubmit,
  initialData,
  loading,
  handleDrop,
  handleFileRemove,
  fileType,
  files,
  persian_fa,
  DatePicker,
  persian,
}) => {
  const { t } = useTranslation()
  const [issueDate, setIssueDate] = useState<DateObject | null>(null)
  const [expireDate, setExpireDate] = useState<DateObject | null>(null)

  const validationSchema = Yup.object().shape({
    license_type: Yup.string().required(t('license.license_type_is_required')),
    issue_date: Yup.string().required(t('license.issue_date_is_required')),
    expire_date: Yup.string().required(t('license.expire_date_is_required')),
  })

  const formik = useFormik({
    initialValues: initialData,
    validationSchema,
    onSubmit: async (values) => {
      await handleSubmit(values)
    },
    enableReinitialize: true,
  })

  const handleDateChange = (date: DateObject | DateObject[] | null, field: string) => {
    if (date && !Array.isArray(date)) {
      const formattedDate = `${date.year}-${date.month.number}-${date.day}`
      formik.setFieldValue(field, formattedDate)
      if (field === 'issue_date') setIssueDate(date)
      if (field === 'expire_date') setExpireDate(date)
    } else {
      formik.setFieldValue(field, '')
      if (field === 'issue_date') setIssueDate(null)
      if (field === 'expire_date') setExpireDate(null)
    }
  }

  const handleFileChange = (newFiles: FileList | null) => {
    if (newFiles) {
      handleDrop(Array.from(newFiles))
    }
  }

  const RequiredLabel = ({ label }: { label: string }) => (
    <FormLabel>
      {label} <span className='text-danger'>*</span>
    </FormLabel>
  )

  useEffect(() => {
    if (!showModal) {
      formik.resetForm()
      setIssueDate(null)
      setExpireDate(null)
    }
  }, [showModal])

  return (
    <Modal show={showModal} onHide={handleClose} size='lg' backdrop='static'>
      <Modal.Header closeButton>
        <Modal.Title>{t('license.create_new_license')}</Modal.Title>
      </Modal.Header>
      <Form onSubmit={formik.handleSubmit}>
        <Modal.Body>
          <Row>
            <Col md={6}>
              <FormGroup className='mb-3'>
                <RequiredLabel label={t('license.issue_date')} />
                <DatePicker
                  calendar={persian}
                  locale={persian_fa}
                  containerStyle={{ width: '100%' }}
                  value={issueDate}
                  placeholder={t('gzlicense.select_issue_date')}
                  style={{ width: '100%', height: '38px' }}
                  onChange={(date: any) => handleDateChange(date, 'issue_date')}
                  format='YYYY-MM-DD'
                />
              </FormGroup>
            </Col>
            <Col md={6}>
              <FormGroup className='mb-3'>
                <RequiredLabel label={t('gzlicense.expire_date')} />
                <DatePicker
                  calendar={persian}
                  locale={persian_fa}
                  containerStyle={{ width: '100%' }}
                  value={expireDate}
                  placeholder={t('gzlicense.select_expire_date')}
                  style={{ width: '100%', height: '38px' }}
                  onChange={(date: any) => handleDateChange(date, 'expire_date')}
                  format='YYYY-MM-DD'
                />
              </FormGroup>
            </Col>
          </Row>
          <Row>
            <Col md={6}>
              <FormGroup className='mb-3'>
                <RequiredLabel label={t('gzlicense.license_type')} />
                <FormSelect
                  name='license_type'
                  value={formik.values.license_type}
                  onChange={formik.handleChange}
                  onBlur={formik.handleBlur}
                  isInvalid={!!(formik.touched.license_type && formik.errors.license_type)}
                >
                  <option value=''>{t('gzlicense.select_license_type')}</option>
                  <option value='new'>{t('gzlicense.new')}</option>
                  <option value='extend'>{t('gzlicense.extend')}</option>
                  <option value='renew'>{t('gzlicense.renew')}</option>
                </FormSelect>
              </FormGroup>
            </Col>
            <Col md={6}>
              <FormGroup className='mb-3'>
                <FormLabel>{t('global.attachments')}</FormLabel>
                <FileUploader
                  handleChange={handleFileChange}
                  name='attachments'
                  types={fileType}
                  multiple={true}
                  maxSize={30}
                  label={t('file_upload.drag_drop_or_click')}
                />

                {files.length > 0 && (
                  <div className='mt-3'>
                    {files.map((file, index) => (
                      <div key={index} className='d-flex justify-content-between'>
                        <span>{file.name}</span>
                        <button
                          type='button'
                          onClick={() => handleFileRemove(index)}
                          className='btn btn-sm btn-link text-danger'
                        >
                          <i className='fa fa-times'></i>
                        </button>
                      </div>
                    ))}
                  </div>
                )}
              </FormGroup>
            </Col>
          </Row>
        </Modal.Body>
        <Modal.Footer>
          <Button variant='primary' type='submit' disabled={loading}>
            {loading ? (
              <>
                <Spinner as='span' size='sm' animation='border' />
                {t('global.saving')}
              </>
            ) : (
              t('global.save')
            )}
          </Button>
          <Button variant='danger' onClick={handleClose} disabled={loading}>
            {t('global.close')}
          </Button>
        </Modal.Footer>
      </Form>
    </Modal>
  )
}

export default LicenseCreateForm
