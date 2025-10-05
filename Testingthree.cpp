#include <iostream>
#include <iomanip>
#include <string>
using namespace std;

int main()
{
    string name;
    int age;
    char gender;
    double income;

    cout << "Enter <name> <age> <gender> <income> :";
    cin >> name >> age >> gender >> income;

    cout << "Name :" << name << endl;
    cout << "Age  :" << age << endl;
    cout << "Gender(M/F) :" << gender << endl;
    cout << fixed << setprecision(2) << endl;
    cout << "Income : " << income << endl;

    return 0;
}
