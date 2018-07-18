// post/post-repository.service.ts
import { Injectable } from '@angular/core';
import { Response } from '@angular/http';
import { AuthHttp } from 'angular2-jwt';

@Injectable()
export class PostRepository {

  constructor(private authHttp: AuthHttp) {}

  getList() {
    let url = 'http://localhost/GJchoc/server/web/app_dev.php/admin/posts';

    return this.authHttp
    .get(url)
    .map((data: Response) => data.json());
  }
}
