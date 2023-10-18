import {Injectable} from '@angular/core';
import {HttpClient} from '@angular/common/http';
import {AbstractCRUDService} from '../../core/crud';
import {ToasterService} from 'angular2-toaster';
import {TitleService} from '../../core/services';
import {JobPostingCvMeta} from './job-posting-cv.meta';

@Injectable()
export class JobPostingCvService extends AbstractCRUDService<JobPostingCvMeta> {

  constructor(http: HttpClient, toaster: ToasterService, title: TitleService) {
    super(http, title, toaster, 'Job Posting CV', 'job_posting_cvs');
  }

}
