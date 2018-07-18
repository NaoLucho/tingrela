// post/post.component.ts
import { Component, OnInit } from '@angular/core';

import { PostRepository } from './post-repository.service';

@Component({
  selector: 'app-post',
  templateUrl: './post.component.html'
})
export class PostComponent implements OnInit {
    posts: any[] = [];
    error: string = '';

    constructor(private postRepository: PostRepository) {}

    ngOnInit() {
        this.postRepository
            .getList()
            .subscribe(
                data => this.posts = data,
                error => this.error = error.message
            );
    }
}
