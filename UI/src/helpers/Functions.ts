import axios from 'axios'
export const downloadFile = (url: any, id: number, file_name: string) => {
  try {
    axios({
      url: `${url}/${id}`,
      method: 'GET',
      responseType: 'blob',
    }).then((response) => {
      const url = window.URL.createObjectURL(new Blob([response.data]))
      const link = document.createElement('a')
      link.href = url
      link.setAttribute('download', file_name)
      document.body.appendChild(link)
      link.click()
    })
  } catch (error) {
 
  }
}
