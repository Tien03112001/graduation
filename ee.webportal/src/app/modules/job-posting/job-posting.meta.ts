import {JobPostingCvMeta} from '../job-posting-cv/job-posting-cv.meta';

export class JobPostingMeta {
  id: number;
  title: string;
  description: string;
  image: string;
  job_location: string;
  base_salary_min: number;
  base_salary_max: number;
  unit_currency: string;
  unit_time: string;
  date_posted: any;
  valid_through: any;
  quantity: number;
  type: string;
  published: boolean;
  job_posting_cvs: JobPostingCvMeta[];
  created_at: string;
  updated_at: string;
}
