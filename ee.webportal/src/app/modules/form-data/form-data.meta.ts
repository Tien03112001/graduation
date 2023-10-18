import {FormMeta} from '../form/form.meta';
import {FormAttributeMeta} from '../form-attribute/form-attribute.meta';

export class FormDataMeta {
  id: number;
  form_id: number;
  attribute_id: number;
  data_id: number;
  form_name: string;
  form_value: any;
  value: string;
  form: FormMeta;
  attribute: FormAttributeMeta;

}
