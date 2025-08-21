import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  vus: 20,
  duration: '1m',
};

export default function () {
  const res = http.get('http://127.0.0.1:8000/', { timeout: '5s' });
  check(res, { 'status 200': (r) => r.status === 200 });
  sleep(1);
}