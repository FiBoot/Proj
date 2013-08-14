#ifndef     LOGWINDOW_H
#define     LOGWINDOW_H

#include    <QDialog>


namespace Ui {
    class LogWindow;
}

class LogWindow : public QDialog
{
    Q_OBJECT
    
public:
    explicit LogWindow(QWidget *parent = 0);
    ~LogWindow();
    
signals:
    void        connection(QString, QString);


private slots:
    void        on_cancelButton_clicked();
    void        on_connectButton_clicked();

private:
    Ui::LogWindow *ui;
};

#endif // LOGWINDOW_H
