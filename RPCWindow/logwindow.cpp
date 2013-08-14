#include        "logwindow.h"
#include        "ui_logwindow.h"


LogWindow::LogWindow(QWidget *parent) : QDialog(parent), ui(new Ui::LogWindow)
{
    ui->setupUi(this);
}

LogWindow::~LogWindow()
{
    delete ui;
}


// slots
void LogWindow::on_cancelButton_clicked()
{
    this->destroy();
}

void LogWindow::on_connectButton_clicked()
{
    emit connection(ui->login->text(), ui->ip->text());
    this->destroy();
}
