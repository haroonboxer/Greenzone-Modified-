import moment from 'jalali-moment'
// helpers/DateConverter.ts
export const to_jalali = (date: string, withTime: boolean): string => {
  if (!date) return withTime ? '--/--/---- --:--' : '--/--/----';
  
  try {
    const convertedDate = withTime 
      ? moment(date).format('jYYYY/jMM/jDD HH:mm')
      : moment(date).format('jYYYY/jMM/jDD');
    
    return convertedDate;
  } catch (error) {
    console.error('Date conversion error:', error);
    return withTime ? '--/--/---- --:--' : '--/--/----';
  }
}
