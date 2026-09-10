import { definePlugin } from '/@src/app';
import { SetupCalendar, Calendar, DatePicker } from 'v-calendar';
import 'v-calendar/dist/style.css';
import idLocale from 'date-fns/locale/id';

export default definePlugin(({ app }) => {
  app.use(SetupCalendar, {
    locale: idLocale,
    masks: {
      input: ["DD-MM-YYYY", "DD/MM/YYYY"],
      inputDateTime: ["DD-MM-YYYY hh:mm:ss A", "DD/MM/YYYY hh:mm:ss A"],
      inputDateTime24hr: ["DD-MM-YYYY HH:mm", "DD/MM/YYYY HH:mm"],
    },
    modelConfig: {
      type: 'string',
      mask: 'YYYY-MM-DD HH:mm:ss',
    },
  });
  app.component('VCalendar', Calendar);
  app.component('VDatePicker', DatePicker);
});
