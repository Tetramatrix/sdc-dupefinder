import { count } from "node:console";

function computeClosestToZero(ts: number[]): number {
    console.log(ts);

    // Write your code here
    // To debug: console.error('Debug messages...');
    for (var idx in ts)
        console.log(ts);
    }

    return 0;
}

/* Ignore and do not change the code below */
// #region main
const n: number = parseInt(readline());
const ts: number[] = readline().split(' ').map(j => parseInt(j)).slice(0, n);
const oldWrite = process.stdout.write;
process.stdout.write = chunk => { console.error(chunk); return true }
const solution: number = computeClosestToZero(ts);
process.stdout.write = oldWrite;
console.log(solution);
// #endregion