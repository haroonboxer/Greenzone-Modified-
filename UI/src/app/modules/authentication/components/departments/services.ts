import axios from 'axios'

export interface Header {
  headerName: string
  sort: string
}

export interface DataItem {
  id: number
  name_da: string
  name_pa: string
  directorateName: string
  code: string
  ownerName: string
}

export const storeDepartment = async (name_da: string, name_pa: string, directorate_id: string) => {
  return await axios.post('api/department/store', {
    name_da: name_da,
    name_pa: name_pa,
    directorate_id: directorate_id,
  })
}
export const updateDepartment = async (
  id: string,
  name_da: string,
  name_pa: string,
  directorate_id: string
) => {
  return await axios.post(`api/department/update/${id}`, {
    name_da: name_da,
    name_pa: name_pa,
    directorate_id: directorate_id,
  })
}

export const serverRequest = (fetchUrl: any, source: any) => {
  return axios.get(fetchUrl, {cancelToken: source})
}

export const getDirectorate = async (id: number) => {
  return await axios.get(`api/directorate/edit/${id}`)
}

export function editDirectorate(
  id: string,
  name_fa: string,
  name_pa: string,
  name_en: string,
  code: string
) {
  return axios.post(`api/directorate/update/` + id, {
    name_fa,
    name_pa,
    name_en,
    code,
  })
}

interface ThemeSettings {
  [key: string]: {
    text: {
      fontSize: string
      fontFamily: string
      color: string
      selectedColor: string
      hoverColor: string
    }
    nodes: {
      height: string
      folder: {
        bgColor: string
        selectedBgColor: string
        hoverBgColor: string
      }
      leaf: {
        bgColor: string
        selectedBgColor: string
        hoverBgColor: string
      }
      separator: {
        border: string
        borderColor: string
      }
      icons: {
        size: string
        folderColor: string
        leafColor: string
      }
    }
  }
}

export const myThemes: ThemeSettings = {
  exampleCustomTheme: {
    text: {
      fontSize: 'xl',
      fontFamily: 'cursive',
      color: '#fafafa',
      selectedColor: '#fafafa',
      hoverColor: '#fafafa',
    },
    nodes: {
      height: '3.5rem',
      folder: {
        bgColor: 'gold',
        selectedBgColor: 'goldenrod',
        hoverBgColor: 'yellow',
      },
      leaf: {
        bgColor: 'magenta',
        selectedBgColor: 'blueviolet',
        hoverBgColor: 'violet',
      },
      separator: {
        border: '3px solid',
        borderColor: 'transparent',
      },
      icons: {
        size: '1rem',
        folderColor: 'crimson',
        leafColor: 'white',
      },
    },
  },
}

export interface NodeModel {
  id: string | number
  label: string
  parentId?: string | number | null
  checked?: boolean
  // Add any other properties you need for your nodes
}
