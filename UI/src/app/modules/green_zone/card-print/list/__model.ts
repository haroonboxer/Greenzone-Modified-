export interface PrintedCard {
  id: string
  weapons: string
  license_type: string
  company_name_dr: string
  issue_date: string
  boss_name_dr?: string
  validity_date: string
  boss_photo?:string
  assistant_name_dr?: string
  company_icon?: string
  assistant_photo?: string
  status: number
  ownerName: string
  created_at: string
  createdLocation: string
  createdDepartment: string
  mainProvince?: string
}

export const defaultPrintedCard: PrintedCard = {
  id: '',
  weapons: '',
  license_type: '',
  company_name_dr: '',
  boss_name_dr: '',
  assistant_name_dr: '',
  boss_photo: '',
  issue_date: '',
  validity_date: '',
  company_icon:'',
  assistant_photo: '',
  status: 0,
  ownerName: '',
  created_at: '',
  createdLocation: '',
  createdDepartment: '',
}

