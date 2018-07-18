import { TestBed, inject } from '@angular/core/testing';

import { PostRepositoryService } from './post-repository.service';

describe('PostRepositoryService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [PostRepositoryService]
    });
  });

  it('should be created', inject([PostRepositoryService], (service: PostRepositoryService) => {
    expect(service).toBeTruthy();
  }));
});
