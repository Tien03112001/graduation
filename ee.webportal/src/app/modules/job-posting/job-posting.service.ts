import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {JobPostingMeta} from './job-posting.meta';

@Injectable()
export class JobPostingService extends AbstractCRUDService<JobPostingMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Tin tuyển dụng', 'job_postings');
  }
}
