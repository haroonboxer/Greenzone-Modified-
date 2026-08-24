import { useEffect, useState } from 'react'
import { useParams } from 'react-router-dom'
import { useTranslation } from 'react-i18next'
import { Link } from 'react-router-dom'
import { useAppDispatch, useAppSelector } from 'redux/hooks'
import Loader from 'app/pages/loading/Loader'
import { to_jalali } from 'helpers/DateConverter'
import RecordOwnerView from 'helpers/CustomeRecordOwnerView'
import VehicleComponentsForm from './VehicleComponentsForm'
  import { viewVehicle } from 'redux/green_zone/vehicles/VehicleSlice'
import { defaultVehicleView, VehicleView } from 'app/modules/green_zone/vehicle/__model'

const VehicleComponents = () => {
  const [loader, setLoader] = useState(true)
  const { id } = useParams()
  const [vehicleData, setVehicleData] = useState<VehicleView>(defaultVehicleView)
  const { vehicleView } = useAppSelector((state) => state.vehicle)
  const dispatch = useAppDispatch()
  const { t } = useTranslation()
  const [isModalOpen, setIsModalOpen] = useState(false)
  const [contentType, setContentType] = useState('')

  const openModal = (contentType = '') => {
    setContentType(contentType)
    setIsModalOpen(true)
  }

  const closeModal = () => {
    setIsModalOpen(false)
    setContentType('')
  }

  useEffect(() => {
    const formData = new FormData()
    if (id) {
      dispatch(viewVehicle({ id, formData } as any))
    }
  }, [id, dispatch])

  useEffect(() => {
    if (vehicleView) {
      setVehicleData(vehicleView);
      setLoader(false);
    }
  }, [vehicleView]);

  return (
    <>
      {!loader ? (
        <VehicleComponentsForm
          t={t}
          Link={Link}
          vehicleData={vehicleData}
          to_jalali={to_jalali}
          id={id}
          openModal={openModal}
          RecordOwnerView={RecordOwnerView}
          isModalOpen={isModalOpen}
          closeModal={closeModal}
        />
      ) : (
        <Loader />
      )}
    </>
  )
}

export default VehicleComponents
