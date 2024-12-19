#include <stdio.h>

typedef struct Path {
    char path[][];
} Path;

 astar();

int main(int argc, char* argv[]) {

    FILE *file = fopen(argv[1], "r");
    if ( file == NULL ) {
        perror("Failed to open file");
    }


    return 0;
}

 Path astar() {

}