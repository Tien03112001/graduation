import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {FreqQuestionMeta} from './freq-question.meta';

@Injectable()
export class FreqQuestionService extends AbstractCRUDService<FreqQuestionMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'câu hỏi thường gặp', 'system_freq_questions');
  }

}
