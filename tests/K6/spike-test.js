import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  stages: [
    { duration: '5s', target: 0 },
    { duration: '10s', target: 10 },  // sudden spike
    { duration: '10s', target: 10 },
    { duration: '5s', target: 0 },
  ],
};

export default function () {
  const res = http.get('http://127.0.0.1:8000/', { timeout: '5s' });
  check(res, { 'status 200': (r) => r.status === 200 });
  sleep(1);
}